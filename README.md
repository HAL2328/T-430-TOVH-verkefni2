## Authors

- Fuad Poroshtica
- Gissur Már Jónsson
- Hallgrímur Jónas Jensson

## Users:

Admin:
- Username: admin
- Password: admin

Editor:
- Username: the_editor
- password: 12345

Writer:
- Username: the_writer
- Password: 12345


## Getting the files:

```bash
git clone https://github.com/FuadPoroshtica/drupal_assignment2.git
cd drupal_assignment2
```
### Adding the Files Folder to `web/sites/default/`

Follow these steps to add the `files` folder to `web/sites/default/`:

1. **Download**:
  - Get `Files.zip` from this link:
    [Download Files.zip](https://github.com/FuadPoroshtica/drupal_assignment2/releases/download/media/Files.zip)

2. **Extract**:
  - Extract `Files.zip` to create a folder named `files`.

3. **Move**:
  - Place the `files` folder in `web/sites/default/`.

    Final structure:
    ```
    web/
      sites/
        default/
          files/
    ```

4. **Verify**:
  - Check your Drupal site to confirm the `files` folder is accessible.

5. **Back to run**:
  - Go back to root of the drupal_assignment2



## Run for the first time:

### First Step:
```bash
ddev composer install
```
### Second Step:
```bash
ddev import-db --file=database/db.sql.gz
```
### Third Step:
```bash
ddev start
```

## After that, perform the following when you run it in the future:
```bash
ddev launch
```

## File Structure:
### [`Tree`](./tree.md)



###### *We love ddev...*
