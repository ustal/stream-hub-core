# stream-hub-core

[![CI](https://github.com/ustal/stream-hub-core/actions/workflows/ci.yml/badge.svg)](https://github.com/ustal/stream-hub-core/actions/workflows/ci.yml)

Framework-agnostic PHP core for plugin-driven stream interfaces.

`stream-hub-core` is not a ready-made chat application. It is a composable core that provides the contracts and orchestration required to build stream-based UIs in Symfony, Laravel, or any custom integration.

It is built around a few core ideas:

- streams contain participants and events;
- all state-changing plugin actions go through a command bus;
- widgets are attached to slots;
- rendering starts from a root page renderer and can be bridged into Twig, Blade, or any other template engine;
- storage, access rules, routing, CSRF, and framework-specific wiring are implemented by the consuming application.

## Current Scope

The package currently provides:

- core models: `Stream`, `StreamEvent`, `StreamParticipant`;
- stream backend contract: `StreamBackendInterface`;
- stream context contract: `StreamContextInterface`;
- plugin contracts and plugin definition pipeline;
- slot tree building and validation;
- command bus and an always-on `CoreStreamPlugin` with basic write commands;
- `StreamRuntime`;
- render orchestration entrypoints:
  - `StreamPageRenderer`
  - `SlotRenderer`
  - `WidgetRenderAdapterInterface`

## Core Concepts

### Stream Model

The core model is intentionally minimal:

- `Stream` contains:
  - `id`
  - `participants`
  - `events`
  - `createdAt`
  - `updatedAt`
- `StreamEvent` contains:
  - `id`
  - `streamId`
  - `userId`
  - `type`
  - `content`
  - `createdAt`
- `StreamParticipant` contains:
  - `userId`
  - `displayName`
  - `active`
  - `createdAt`
  - `settings`
  - `leftAt`
  - `lastReadAt`

These are core models, not persistence models. Your application is free to store richer data in MongoDB, SQL, REST backends, or anything else, then map it into these models.

### Streams and Events

- A stream is a container for interaction.
- An event is the generic unit inside a stream.
- User messages and system messages are both stream events.
- The core event type enum currently provides:
  - `message`
  - `system`

### Plugins

Plugins are the main extension unit.

A plugin may provide:

- command handlers;
- widgets;
- JS assets;
- CSS assets;
- template files for one or more rendering bridges.

The package also includes an always-on `CoreStreamPlugin`. It is registered automatically and cannot be disabled. It provides the basic write-side stream commands and handlers.

### Slots and Widgets

Widgets attach to target slots and may provide child slots.

Slot semantics are split into two different concepts:

- `WidgetPlacementMode`
  - how a widget wants to attach to a target slot
  - `append` or `replace`
- `SlotAcceptanceMode`
  - what the provided slot allows others to do
  - `append-only`, `replace-only`, or `any`

The slot tree is validated before runtime use.

### Rendering

The root page render is not a widget.

Rendering currently works like this:

1. `StreamPageRenderer` renders the root wrapper.
2. It starts rendering from the `main` root slot.
3. `SlotRenderer` resolves widgets for the slot from the validated slot tree.
4. `WidgetRenderAdapterInterface` is responsible for rendering each widget with the actual template engine.

Nested slot rendering is expected to be implemented by bridges. For example:

- a Twig bridge can expose a `renderSlot(...)` function;
- a Blade bridge can expose a directive or helper with the same purpose.

The core does not know Twig or Blade syntax.

## What the Integrator Must Provide

To use this package in a real application you need to provide the following:

### 1. `StreamBackendInterface`

Your backend implementation must handle stream operations:

- create a stream;
- join a stream;
- fetch one stream;
- fetch the current stream list;
- append an event;
- mark a stream as read;
- provide unread stream and unread event counters.

This is the main backend contract used by the core runtime and handlers.

### 2. `StreamContextInterface`

Your context implementation bridges application data into the core.

It currently provides:

- current user id;
- current actor;
- URL generation;
- CSRF token lookup;
- arbitrary key-value access for adapter-specific values.

This context is passed through runtime, backend calls, command handlers, and widget rendering.

### 3. `WidgetRenderAdapterInterface`

This is the rendering bridge between the core and your template engine.

Examples:

- Twig adapter;
- Blade adapter;
- custom renderer.

The adapter is responsible for:

- locating the right widget template;
- rendering it with the template engine;
- exposing nested slot rendering helpers if needed.

### 4. Plugin Configuration

Your application decides which plugins are enabled.

The typical flow is:

1. collect enabled plugin classes;
2. build `PluginDefinitionRegistry`;
3. build and validate the slot tree;
4. create `PluginManager` for resolved plugin metadata and assets.

### 5. Command Bus Wiring

You must instantiate the command handlers that should exist in your application and build the bus through:

- `PluginDefinitionBuilder`
- `CommandBusFactory`

The factory ensures only handlers from enabled plugins are accepted.

### 6. Root Render Wiring

To render a page you need to wire:

- `SlotTree`
- `Renderer`
- `WidgetRenderAdapterInterface`
- `SlotRenderer`
- `StreamPageRenderer`

## Minimal Bootstrap Flow

At a high level, a minimal setup looks like this:

1. Implement `StreamBackendInterface`.
2. Implement `StreamContextInterface`.
3. Choose enabled plugins.
4. Build plugin definitions.
5. Build slot tree.
6. Build plugin manager.
7. Create command handlers and build the command bus.
8. Open a `StreamRuntime` through `StreamService`.
9. Render the page through `StreamPageRenderer`.

## Assets

The core does not generate framework-specific public asset paths.

`PluginManager::getPublicAssets()` returns a neutral asset map for enabled plugins:

- plugin name;
- plugin class;
- JS files;
- CSS files.

Any normalization, filesystem layout, bundling, or public path generation must be handled by the consuming framework or build tooling.

## Commands

The always-on `CoreStreamPlugin` currently provides the write-side foundation:

- `CreateStreamCommand`
- `JoinStreamCommand`
- `AppendStreamEventCommand`
- `MarkStreamReadCommand`

Read-side operations remain direct service/backend operations.

## Development

Run tests:

```bash
make test
```
