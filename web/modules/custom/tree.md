.
├── music_search
│   ├── composer.json
│   ├── modules
│   │   ├── discogs_lookup
│   │   │   ├── config
│   │   │   │   ├── Install
│   │   │   │   │   └── discogs_lookup.settings.yml
│   │   │   │   └── schema
│   │   │   │       └── discogs_lookup.schema.yml
│   │   │   ├── discogs_lookup.info.yml
│   │   │   ├── discogs_lookup.links.menu.yml
│   │   │   ├── discogs_lookup.module
│   │   │   ├── discogs_lookup.routing.yml
│   │   │   ├── discogs_lookup.services.yml
│   │   │   └── src
│   │   │       ├── DiscogsLookupService.php
│   │   │       ├── DiscogsResultParser.php
│   │   │       └── Form
│   │   │           └── DiscogsSettingsForm.php
│   │   └── spotify_lookup
│   │       ├── config
│   │       │   ├── Install
│   │       │   │   └── spotify_lookup.settings.yml
│   │       │   └── schema
│   │       │       └── spotify_lookup.schema.yml
│   │       ├── spotify_lookup.info.yml
│   │       ├── spotify_lookup.links.menu.yml
│   │       ├── spotify_lookup.module
│   │       ├── spotify_lookup.routing.yml
│   │       ├── spotify_lookup.services.yml
│   │       └── src
│   │           ├── Form
│   │           │   └── SpotifySettingsForm.php
│   │           ├── SpotifyLookupService.php
│   │           ├── SpotifyResultParser.php
│   │           ├── SpotifyTokenService.php
│   │           └── SpotifyUriExtractor.php
│   ├── music_search.info.yml
│   ├── music_search.libraries.yml
│   ├── music_search.links.menu.yml
│   ├── music_search.module
│   ├── music_search.routing.yml
│   ├── music_search.services.yml
│   ├── music_search.theme
│   └── src
│       ├── Controller
│       │   ├── FieldSelectorController.php
│       │   ├── MusicSearchController.php
│       │   └── MusicSearchResultsController.php
│       ├── Form
│       │   ├── EntityFieldSelectorForm.php
│       │   ├── MusicSearchForm.php
│       │   ├── MusicSearchSelectionForm.php
│       │   └── MusicSearchSettingsForm.php
│       ├── MusicSearchService.php
│       └── SearchServiceInterface.php
└── tree.md

18 directories, 40 files
