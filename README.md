## Authors:

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

## Installing and running the Application
### Getting the files:

```bash
git clone https://github.com/HAL2328/T-430-TOVH-verkefni2
cd T-430-TOVH-verkefni2
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

4. **Back to run**:
  - Go back to root of T-430-TOVH-verkefni2



### Run for the first time:

#### First Step:
```bash
ddev composer install
```
#### Second Step:
```bash
ddev import-db --file=database/database.sql.gz
```
#### Third Step:
```bash
ddev start
```

### After that, perform the following when you run it in the future:
```bash
ddev launch
```


## Using the Music Search Module

To use the music search module:
- Log in as admin
- Navigate to install extension
- Install the Music Search module
- Install the Spotify Lookup and Discogs Lookup services
- Under *Configuration / Web services* you will now find *Discogs Lookup Settings* and *Spotify Lookup Settings*
    - Use these to enter *Consumer Key* and *Consumer Secret* for Discogs and *ClientID* and *ClientSecret* for Spotify to generate a temporary access token
    - You can also use relative URLs:
 ```bash
/admin/config/music-search/discogs
/admin/config/music-search/spotify
```
- You can now use the *Music Search*, found under the *Content* menu
    - Or the relative path:
 ```bash
/music-search/search
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

## Hugsanleg villa sem gæti komið upp:
Stundum gerðist það að villa kom upp þegar reynt var að opna *node.add.**

Þá reyndist
 ```bash
$entity->moderation_state
```
stundum vera *'null'*. Þetta var leyst með því að fara í skjal:
 ```bash
web/core/modules/content_moderation/src/EntityTypeInfo.php
```

Þar er lína 367:
 ```bash
$form['meta']['published']['#markup'] =
    $this->moderationInfo->getWorkflowForEntity($entity)->
        getTypePlugin()->getState($entity->moderation_state->value)->label();

```
Þessari línu breyttum við svona:

 ```bash
if (isset ($entity->moderation_state->value)) {
  $form['meta']['published']['#markup'] =
    $this->moderationInfo->getWorkflowForEntity($entity)->
      getTypePlugin()->getState($entity->moderation_state->value)->label();
}
```

og virtist það leysa málið.

---



###### *We love ddev...*
