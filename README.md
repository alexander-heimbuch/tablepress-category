# Tablepress Categories
*Create categories in TablePress to show and hide rows*

Extends the [TablePress](https://tablepress.org/) plugin with the ability to open and close categories of rows.

## Usage

```
[category-table id=1]
    [category name="Category 1" row_start=1 row_end=5 opened="true"/]
    [category name="Category 2" row_start=6 row_end=10/]
    [category name="Category 3" row_start=11 row_end=16/]
[/category-table]
```

``category-table`` is a ``table`` wrapper and accepts all valid TablePress attributes.

## Installation

Simply download and install the plugin. It requires the TablePress plugin and activated DataTables.
