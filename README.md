# staticco
A PHP static code analyzer based on the fantastic [PHP-Parser](https://github.com/nikic/PHP-Parser/) library.

### Features
 - Find the classes declared in a PHP file.
 - Find the classes that this PHP file depends on.

### Usage:
```bash
$ php ./app.php --file="filename"
$ php ./app.php --dir="dirname" [options]

Options
  --recursive       Navigates through the directory recursively
  --follow_links    Follows symbolic links
  --extension="php" Filters the files analyzed to those matching this extension.
                    Multiple `--extension` flags are supported.
  --
```

### Usage examples
```bash
$ php ./app.php --dir="home/root/my_project_dir" --recursive --extension="php" --extension="ctp"
$ php ./app.php --file="home/root/my_project_dir/web/index.php"

```

### To do
Support for injecting custom traversers via command-line arguments
