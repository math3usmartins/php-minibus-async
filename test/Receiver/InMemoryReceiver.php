<?php

namespace MiniBus\Test\Transport\Receiver;

use MiniBus\Envelope;
use MiniBus\Envelope\EnvelopeCollection;
use MiniBus\Test\Envelope\Stamp\StubStamp;
use MiniBus\Transport\Receiver;
use function count;
use function in_array;

final class InMemoryReceiver implements Receiver
{
    const IN_MEMORY_ID_STAMP_NAME = 'in-memory-id';

    /**
     * @var EnvelopeCollection
     */
    private $envelopes;

    public function __construct(EnvelopeCollection $envelopes)
    {
        $this->envelopes = self::identifiable($envelopes);
    }

    /**
     * @return EnvelopeCollection
     */
    public static function identifiable(EnvelopeCollection $envelopes)
    {
        return array_reduce(
            $envelopes->items(),
            function (EnvelopeCollection $envelopes, Envelope $envelope) {
                return $envelopes->with(
                    $envelope->withStamp(
                        new StubStamp(
                            self::IN_MEMORY_ID_STAMP_NAME,
                            (string) count($envelopes->items())
                        )
                    )
                );
            },
            new EnvelopeCollection([])
        );
    }

    /**
     * @return EnvelopeCollection
     */
    public function fetch()
    {
        return $this->envelopes;
    }

    public function ack(EnvelopeCollection $envelopes)
    {
        $ackIdValues = self::getIdValues($envelopes);

        $remaining = array_values(
            array_filter(
                $this->envelopes->items(),
                function (Envelope $current) use ($ackIdValues) {
                    /** @var StubStamp $stamp */
                    $stamp = $current->stamps()->last(self::IN_MEMORY_ID_STAMP_NAME);

                    return !in_array($stamp->keyValue(), $ackIdValues, true);
                }
            )
        );

        $this->envelopes = new EnvelopeCollection($remaining);
    }

    public function reject(EnvelopeCollection $envelopes)
    {
        $rejectedIdValues = self::getIdValues($envelopes);

        $remaining = array_values(
            array_filter(
                $this->envelopes->items(),
                function (Envelope $current) use ($rejectedIdValues) {
                    /** @var StubStamp $stamp */
                    $stamp = $current->stamps()->last(self::IN_MEMORY_ID_STAMP_NAME);

                    return !in_array($stamp->keyValue(), $rejectedIdValues, true);
                }
            )
        );

        $this->envelopes = new EnvelopeCollection($remaining);
    }

    public function retry(EnvelopeCollection $envelopes)
    {
        // nothing to do in this case,
        // current collection MUST NOT change
    }

    /**
     * @return string[]
     */
    private static function getIdValues(EnvelopeCollection $envelopes)
    {
        return array_map(
            function (Envelope $envelope) {
                /** @var StubStamp $stamp */
                $stamp = $envelope->stamps()->last(self::IN_MEMORY_ID_STAMP_NAME);

                return $stamp->keyValue();
            },
            $envelopes->items()
        );
    }
}
