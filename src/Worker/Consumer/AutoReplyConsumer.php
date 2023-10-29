<?php

declare(strict_types=1);

namespace MiniBus\Transport\Worker\Consumer;

use Exception;
use MiniBus\Dispatcher\DefaultDispatcher;
use MiniBus\Envelope;
use MiniBus\Envelope\EnvelopeCollection;
use MiniBus\Transport\Receiver;
use MiniBus\Transport\Sender\SenderStamp;
use MiniBus\Transport\Worker\Consumer;
use MiniBus\Transport\Worker\Consumer\Stamp\FailedStamp;
use MiniBus\Transport\Worker\Consumer\Stamp\SuccessfulStamp;

final class AutoReplyConsumer implements Consumer
{
    public function __construct(
        private DefaultDispatcher $dispatcher,
        private RetryStrategy $retryStrategy,
    ) {}

    public function consume(Receiver $receiver): EnvelopeCollection
    {
        $envelopes = $receiver->fetch()->map(
            fn (Envelope $envelope) => $this->tryToConsumeEnvelope($receiver, $envelope),
        );

        $failedEnvelopes = $envelopes->filter(
            static fn (Envelope $envelope) => null !== $envelope->stamps()->last(FailedStamp::NAME),
        );

        $this->rejectOrRetryFailedEnvelopes($receiver, $failedEnvelopes);

        $successfulEnvelopes = $envelopes->filter(
            static fn (Envelope $envelope) => $envelope->stamps()->contains(new SuccessfulStamp()),
        );

        $receiver->ack($successfulEnvelopes);

        return $envelopes;
    }

    private function rejectOrRetryFailedEnvelopes(
        Receiver $receiver,
        EnvelopeCollection $failedEnvelopes,
    ): void {
        $retryResponse = $this->retryStrategy->check($failedEnvelopes);

        $receiver->reject($retryResponse->findNotRetriable());
        $receiver->retry($retryResponse->findRetriable());
    }

    private function tryToConsumeEnvelope(Receiver $receiver, Envelope $envelope): Envelope
    {
        $alreadySentEnvelope = $envelope->withStamp(new SenderStamp());

        try {
            $result = $this->dispatcher->dispatch($alreadySentEnvelope->message(), $alreadySentEnvelope->stamps());

            return $result->withStamp(new SuccessfulStamp());
        } catch (Exception $exception) {
            return $alreadySentEnvelope->withStamp(new FailedStamp($receiver, $exception));
        }
    }
}
