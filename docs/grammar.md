# Grammar

A valid file consists of one or more statements.

A statement is an optional comment, followed by a word, followed by zero or more
expressions, terminated with a semicolon.

An expression is a word, number, string, block, list, map, or other mark.

A block is a left curly brace, followed by one or more statements, followed by a
right curly brace.

A list is a left parenthesis, followed by one or more expressions, followed by a
right parenthesis.

A map is a left square bracket, followed by one or more mappings, followed by a
right square bracket. A mapping is a word followed by a colon followed by an
expression.

An other mark is a punctuation mark not used to mark a string, block, list, or
map.

## SQL Example

```
select * from Customers;

select (cus_name city) from Customers;

select distinct country from Customers;

select * from Customers where (eq country Mexico);

select * from Products order_by price;

select * from Customers where (and (eq country Mexico) (like cus_name "J%"));
```

## PHP Example

```
if (eq x 1) {
    (print y)
};

class (abstract) [extends: Super implements: MyInterface] {
    int (protected) x;
    string (protected) y;
    function (public) [int: x string: y] {
        set (this x) x;
        set (this y) y;
        set z (plus x y);
        return z;
    };
};
```
