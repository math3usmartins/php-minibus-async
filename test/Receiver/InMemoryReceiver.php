<?php

declare(strict_types=1);

namespace MiniBus\Test\Transport\Receiver;

use MiniBus\Envelope;
use MiniBus\Envelope\EnvelopeCollection;
use MiniBus\Test\Envelope\Stamp\StubStamp;
use MiniBus\Transport\Receiver;

use function count;
use function in_array;

final class InMemoryReceiver implements Receiver
{
    public const IN_MEMORY_ID_STAMP_NAME = 'in-memory-id';

    private \MiniBus\Envelope\EnvelopeCollection $envelopes;

    public function __construct(EnvelopeCollection $envelopes)
    {
        $this->envelopes = self::identifiable($envelopes);
    }

    public static function identifiable(EnvelopeCollection $envelopes): EnvelopeCollection
    {
        return array_reduce(
            $envelopes->items(),
            static fn (EnvelopeCollection $envelopes, Envelope $envelope) => $envelopes->with(
                $envelope->withStamp(
                    new StubStamp(
                        self::IN_MEMORY_ID_STAMP_NAME,
                        (string) count($envelopes->items()),
                    ),
                ),
            ),
            new EnvelopeCollection([]),
        );
    }

    public function fetch(): EnvelopeCollection
    {
        return $this->envelopes;
    }

    public function ack(EnvelopeCollection $envelopes): void
    {
        $ackIdValues = self::getIdValues($envelopes);

        $remaining = array_values(
            array_filter(
                $this->envelopes->items(),
                static function (Envelope $current) use ($ackIdValues) {
                    /** @var StubStamp $stamp */
                    $stamp = $current->stamps()->last(self::IN_MEMORY_ID_STAMP_NAME);

                    return !in_array($stamp->keyValue(), $ackIdValues, true);
                },
            ),
        );

        $this->envelopes = new EnvelopeCollection($remaining);
    }

    public function reject(EnvelopeCollection $envelopes): void
    {
        $rejectedIdValues = self::getIdValues($envelopes);

        $remaining = array_values(
            array_filter(
                $this->envelopes->items(),
                static function (Envelope $current) use ($rejectedIdValues) {
                    /** @var StubStamp $stamp */
                    $stamp = $current->stamps()->last(self::IN_MEMORY_ID_STAMP_NAME);

                    return !in_array($stamp->keyValue(), $rejectedIdValues, true);
                },
            ),
        );

        $this->envelopes = new EnvelopeCollection($remaining);
    }

    public function retry(EnvelopeCollection $envelopes): void
    {
        // nothing to do in this case,
        // current collection MUST NOT change
    }

    /**
     * @return string[]
     */
    private static function getIdValues(EnvelopeCollection $envelopes): array
    {
        return array_map(
            static function (Envelope $envelope) {
                /** @var StubStamp $stamp */
                $stamp = $envelope->stamps()->last(self::IN_MEMORY_ID_STAMP_NAME);

                return $stamp->keyValue();
            },
            $envelopes->items(),
        );
    }
}
