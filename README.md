# PHP MiniBus: Transport

[![test](https://github.com/math3usmartins/php-minibus-async/actions/workflows/test.yaml/badge.svg)](https://github.com/math3usmartins/php-minibus-async/actions/workflows/test.yaml)

`php-minibus-transport` provides classes and interfaces to send messages to
other apps via message brokers like RabbitMQ, Kafka, AWS SQS/SNS etc.

## The big picture

```mermaid
sequenceDiagram
    autonumber
    participant YourApp
    participant MessageDispatcher
    participant MiddlewareStack
    participant TransportHandler
    participant TransportSender
    participant MessageBroker
    YourApp ->> MessageDispatcher: dispatch(message)
    MessageDispatcher ->> MiddlewareStack: handle(envelope)
    MiddlewareStack ->> TransportHandler: handle(envelope)
    TransportHandler ->> MessageBroker: send(envelope)
    MiddlewareStack ->> YourApp: finalEnvelopeWithSomeStamps
    box minibus
        participant MessageDispatcher
        participant MiddlewareStack
        participant TransportHandler
        participant TransportSender
    end
```

p.s. in addition to [Sender](src/Sender.php) `php-minibus-transport` also
provides [Receiver](src/Receiver.php) so that an app can fetch messages from
a message broker.
