PHP Diagrams
============

[![Build Status](https://travis-ci.org/dantleech/php-diagrams.svg?branch=master)](https://travis-ci.org/dantleech/php-diagrams)

Library to generate flow chart diagrams from a [Mermaid
DSL](https://mermaid-js.github.io/mermaid/#/flowchart) inspired DSL.

Disclaimer
----------

This is a quick proof of concept.

Usage
-----

Given `example/mermaid.mm`:

```
graph TD
  A[Christmas] -->|Get money| B[Go shopping]
  B --> C{Let me think}
  C -->|One| D[Laptop]
  C -->|Two| E[iPhone]
  C -->|Three| F[Car]
```

Run:

```bash
$ diagram generate example/mermaid.mm out.png 
```

To generate:

![image](https://user-images.githubusercontent.com/530801/92037904-6e3c8e00-ed6a-11ea-8337-2bf2e0132407.png)

Supported Markup
----------------

All graphs must start with `graph TD` (`TD` is the orientation, but only one
orientation is supported)

Standard nodes

```
foobar --> barfoo
```

Rectangle with text

```
foobar[This is Foobar] --> barfoo
```

Rhombus with text

```
foobar{This is Foobar} --> barfoo
```

Label for edge:

```
foobar{This is Foobar} -->|and it goes to| barfoo[Barfoo]
```
