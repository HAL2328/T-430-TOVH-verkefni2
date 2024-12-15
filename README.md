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
___
## File Structure:

### Root Level
- `composer.json`
- `music_search.info.yml`
- `music_search.links.menu.yml`
- `music_search.module`
- `music_search.routing.yml`
- `music_search.services.yml`
- `music_search.theme`

### modules:
- **discogs_lookup**
  - **config**
    - **Install**
      - `discogs_lookup.settings.yml`
    - **schema**
      - `discogs_lookup.schema.yml`
  - `discogs_lookup.info.yml`
  - `discogs_lookup.links.menu.yml`
  - `discogs_lookup.module`
  - `discogs_lookup.routing.yml`
  - `discogs_lookup.services.yml`
  - **src**
    - `DiscogsLookupService.php`
    - `DiscogsResultParser.php`
    - **Form**
      - `DiscogsSettingsForm.php`
- **spotify_lookup**
  - **config**
    - **Install**
      - `spotify_lookup.settings.yml`
    - **schema**
      - `spotify_lookup.schema.yml`
  - `spotify_lookup.info.yml`
  - `spotify_lookup.links.menu.yml`
  - `spotify_lookup.module`
  - `spotify_lookup.routing.yml`
  - `spotify_lookup.services.yml`
  - **src**
    - **Form**
      - `SpotifySettingsForm.php`
      - `SpotifyLookupService.php`
      - `SpotifyResultParser.php`
      - `SpotifyTokenService.php`
      - `SpotifyUriExtractor.php`
### src
- **Controller**
  - `MusicSearchController.php`
  - `MusicSearchResultsController.php`
- **Form**
  - `EntityFieldSelectorForm.php`
  - `MusicSearchForm.php`
  - `MusicSearchSelectionForm.php`
  - `MusicSearchSettingsForm.php`
- `MusicSearchService.php`
- `SearchServiceInterface.php`


---



###### *We love ddev...*
