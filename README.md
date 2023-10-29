# PHP MiniBus: Transport

[![test](https://github.com/math3usmartins/php-minibus-async/actions/workflows/test.yaml/badge.svg)](https://github.com/math3usmartins/php-minibus-async/actions/workflows/test.yaml)

`php-minibus-transport` provides classes and interfaces to send or receive
messages to other apps or systems, so they can be handled externally or
asynchronously, via message broker or such.

## The big picture

### Send messages

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
    MiddlewareStack ->> MessageBroker: send(envelope)
    TransportSender ->> YourApp: finalEnvelopeWithSomeStamps
    box minibus
        participant MessageDispatcher
        participant MiddlewareStack
        participant TransportHandler
        participant TransportSender
    end
```

### Worker: Fetch and dispatch envelopes

```mermaid
sequenceDiagram
    autonumber
    participant MessageBroker
    participant TransportReceiver
    participant TransportConsumer
    participant MessageDispatcher
    participant MiddlewareStack
    TransportConsumer ->> MessageBroker: fetch()
    loop
        TransportConsumer ->> MiddlewareStack: dispatch(envelope)
        MessageDispatcher ->> MiddlewareStack: handle(envelope)
    end
    box worker
        participant TransportConsumer
        participant TransportReceiver
    end
    box dispatcher
        participant MessageDispatcher
        participant MiddlewareStack
    end
```
