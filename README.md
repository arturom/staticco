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
  --extension="php" Filters the files analyzed to those matching this extension
                    Multiple `--extension` flags are supported
```

### Usage examples
```bash
$ php ./app.php --dir="home/root/my_project_dir" --recursive --extension="php" --extension="ctp"
$ php ./app.php --file="home/root/my_project_dir/web/index.php"

```

### To do
Support for injecting custom traversers via command-line arguments


### Sample output
```bash
$ php ./app.php --file /root/app/base_model.php
file: /root/app/base_model.php

Declared Classes
  line | name
     3 | RealtimeModel

Referenced names
  line | name
     3 | Model
    13 | ClassRegistry
    15 | ClassRegistry
    16 | ClassRegistry
    22 | ClassRegistry
   127 | Exception
   355 | ClassRegistry
   360 | Redisent
   360 | Configure
   360 | Configure
   361 | ClassRegistry

Cake imports
  line | name
    22 | ClassRegistry:init($modelClassName)
   132 | $this:loadModel($modelName)
   165 | $this:loadModel($hasManyModelName)
```
