<?php


namespace App\Messenger;


use App\Message\Command\LogEmoji;
use Exception;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class ExternalJsonMessengerSerializer implements SerializerInterface
{

    public function decode(array $encodedEnvelope): Envelope
    {
        $body = $encodedEnvelope['body'];
        $headers = $encodedEnvelope['headers'];
        $data = json_decode($body, true);

        if (null === $data) {
            throw new MessageDecodingFailedException('Invalid JSON');
        }

        if (!isset($data['emoji'])) {
            throw new MessageDecodingFailedException('Missing the emoji key');
        }

        if (!isset($headers['type'])) {
            throw new MessageDecodingFailedException('Missing "type" header');
        }

        switch ($headers['type']) {
            case 'emoji':
                return $this->createLogEmojiEnvelope($data);
            case 'delete_photo':
                break;
        }

        throw new MessageDecodingFailedException('Invalid type "%s"', $headers['type']);
    }

    public function encode(Envelope $envelope): array
    {
        throw new Exception('Transport & serializer not meant for sending message');
    }

    /**
     * @param $data
     * @return Envelope
     */
    public function createLogEmojiEnvelope(array $data): Envelope
    {
        $message = new LogEmoji($data['emoji']);
        // in case of redelivery, unserialize any stamps
        $stamps = [];
        if (isset($headers['stamps'])) {
            $stamps = unserialize($headers['stamps']);
        }
        $envelope = new Envelope($message, $stamps);

        //needed only if you need this ti be sent through the non-defaul bus
        $envelope = $envelope->with(new BusNameStamp('command.bus'));

        return $envelope;
    }
}