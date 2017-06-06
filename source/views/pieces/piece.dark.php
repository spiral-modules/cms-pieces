<?php #compile
ob_start(); ?>${id}${name}<?php $id = ob_get_clean(); #compile
ob_start(); ?>${context}<?php $content = ob_get_clean(); #compile

/** @var \Spiral\Pieces\Pieces $pieces */
$pieces = spiral(\Spiral\Pieces\Pieces::class);

/** @var \Spiral\Views\ViewSource $view*/
$piece = $pieces->getPiece($id, $content, $view->getName(), $view->getNamespace());

/* @var \Spiral\Views\DynamicEnvironment $environment */

if ($environment->getValue('cms.editable')) : ?>
    <div data-piece="${piece-type|html}" data-id="<?= $piece->getCode() #compile ?>"
         node:attributes>
        <?= $piece->getContent() #compile ?>
    </div>
<?php else: #compile ?>
    <div>
        <?= $piece->getContent(); #compile ?>
    </div>
<?php endif; #compile ?>
