<?php
/**
 * CMS pieces component configuration file. Attention, configs might include runtime code
 * which depended on environment values only.
 *
 * @see PiecesConfig
 */

return [
    /*
     * Permission which is required for piece editing.
     */
    'permission' => 'vault.pieces.edit',

    /*
     * Image uploads.
     */
    'images'     => [
        'storage'    => 'cms.images',
        'thumbnails' => [
            'width'  => 120,
            'height' => 120
        ]
    ]
];
