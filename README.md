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
