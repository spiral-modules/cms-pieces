<?php #compile
ob_start(); ?>${id}${name}<?php $id = ob_get_clean(); #compile
ob_start(); ?>${context}<?php $content = ob_get_clean(); #compile

/** @var \Spiral\Pieces\Pieces $pieces */
$pieces = spiral(\Spiral\Pieces\Pieces::class);
$piece = $pieces->getPiece($id, $content, $this->view, $this->namespace);

if ($pieces->canEdit()) {
    ?>
    <div data-piece="html" data-id="<?= $piece->getCode() #compile ?>">
        <?= $piece->getContent() #compile ?>
    </div>
    <?php #compile
} else {
    echo $piece->getContent();
}
?>