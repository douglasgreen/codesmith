# codesmith

A project to read a simple universal syntax and write other languages.

This project exists because I wanted to do linting and formatting on various languages. This is a
hard problem because each language is designed as a pile of special syntaxes. Those syntaxes are
hard to parse. So instead I designed a simple universal syntax (SUS) with the goal of writing the
languages rather than reading them. Writing is an easier problem than reading. In addition, I can
choose only the features of each language that I want to support.

SUS is a simple hybrid of Lisp and C. From Lisp it borrows the idea that everything is a list or a
map. And from C it borrows the idea that all code is a series of statements terminated by semicolons
or statement blocks surrounded by braces. This avoids the monotony of Lisp where everything is
parentheses. Lisp fails to distinguish between microstructure (the parentheses or brackets) and
macro structure (the braces) which makes it monotonous and unreadable. Its functional design also
leads to excessive nesting of parentheses rather than the simple procedural design of C with its
independent statements.

SUS is designed to be simple and unambiguous. It's parsed with a top-down recursive parser. Once you
produce the syntax tree, you can do all the linting and checking you want. Then you can produce a
well formatted output in the target language. This is much simpler than the original problem of
having to parse each language and write special purpose linters on different syntax trees.

The major drawback is that SUS is a new syntax so it isn't well supported by the toolchain such as
code editors, code generators, and LLMs. However it has a minor usefulness and is easy to learn. And
it illustrates the idea that a language can be designed as a generic set of repeating syntax
elements rather than as an ever-expanding pile of special cases.

For more information about SUS, see its [Grammar](docs/grammar.md).

## Project setup

Standard config files for linting and testing are copied into place from a GitHub repository called
[config-setup](https://github.com/douglasgreen/config-setup). See that project's README page for
details.
