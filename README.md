# stream-hub-core

[![CI](https://github.com/ustal/stream-hub-core/actions/workflows/ci.yml/badge.svg)](https://github.com/ustal/stream-hub-core/actions/workflows/ci.yml)

Framework-agnostic headless core for Stream Hub.

`stream-hub-core` is no longer positioned as a pluggable UI engine. In the `v1` direction it focuses on stream models, low-level write commands, command buses, backend contracts, and reusable framework-agnostic building blocks.

## Current Scope

The package currently provides:

- core models:
  - `Stream`
  - `StreamEvent`
  - `StreamParticipant`
- backend contract:
  - `StreamBackendInterface`
- application context contract:
  - `StreamContextInterface`
- command contracts:
  - `StreamCommandInterface`
  - `StreamCommandHandlerInterface`
- buses:
  - `CommandBusInterface`
  - `ModelCommandBusInterface`
  - `CommandBus`
  - `GuardedCommandBus`
- thin application facade:
  - `StreamHubInterface`
  - `StreamHub`
  - `viewStream()` for read-and-mark-read flow
- guard contracts for high-level commands:
  - `StreamCommandGuardInterface`
  - `GuardDecision`
- low-level core commands and handlers:
  - create stream
  - join stream
  - append stream event
  - mark stream read
- identifier generation:
  - `IdentifierGeneratorInterface`
  - `RandomHexIdentifierGenerator`
  - `UuidV4IdentifierGenerator`
  - `UuidV7IdentifierGenerator`

## Design Notes

- Core models are not persistence entities.
- Project-specific enrichment belongs in backend adapters and mappers.
- High-level feature commands may be guarded before handler execution.
- Low-level model commands should stay internal and should not be guarded.
- High-level feature workflows should orchestrate low-level core commands instead of writing directly to storage when a generic low-level command already exists.
- Business authorization rules should live in the application or policy layer, not in the backend contract.

## Development

Run tests:

```bash
make test
```

Run deptrac:

```bash
vendor/bin/deptrac analyse
```
