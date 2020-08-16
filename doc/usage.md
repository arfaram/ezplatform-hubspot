## Hubspot Interface

1. Create your API Token
2. Add your social channels 

## ContentTypes and channels Configuration

1.This bundle supports now only below FieldTypes:
- `Text Line` (ezstring): The text to share
- `Image` (ezimage) and `ImageAsset` (ezimageasset): The image to share

2.Configure the `body` or/and the `photoUrl` using valid FieldType identifiers. Note that both field **values** (text or image to share) must not be empty.

3.You can enable or disable any providers via the `enabled` option. The channel will be then displayed or hidden from the context menu in the right content view sidebar.

Note that the channels name **must not** be modified e.g `facebook`, `linkedinstatus`

- mapping example:
```
ezplatform:
    system:
        default:
            hubspot_config:
                content_types_map:
                    news:
                        facebook:
                            body: 'title'
                            photoUrl: 'image'
                            enabled: true
                        instagram:
                            body: 'title'
                            photoUrl: 'image'
                            enabled: true
                        twitter:
                            body: 'title'
                            photoUrl: 'image'
                            enabled: false
                    career:
                        twitter:
                            body: 'title'
                            photoUrl: 'image'
                            enabled: true
                        linkedinstatus:
                            body: 'title'
                            photoUrl: 'image'
                            enabled: true
```

## Users and groups Policies

You can grant access to the different users(groups) to the Hubspot management interface in `Admin > URL Management` by adding following policy

```
Hubspot:
    'Hubspot / View'
    'Hubspot / Dashboard'
    'Hubspot / Social'
    'Hubspot / Settings'
```

To be able to see the context menu in the content view you have to grant below policy to the different users(groups).
```
Content:
    'Content / Push Hubspot'
```

## Notes
- If you delete an item fom the trash or empty the whole trash all social information about the content in question will be also deleted from the database.

## Deprecations
Hubspot API
- Please note following future deprecation in the [Hubspot API](https://developers.hubspot.com/docs/api/deprecated-apis). They were announced during the development of this bundle. Hubspot doesn't provide any information about future alternative solution.
- Likes and comments fields are deprecated, and will always be set to null
