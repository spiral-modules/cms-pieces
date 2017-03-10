<?php #compile
ob_start(); ?>${id}${name}<?php $id = ob_get_clean(); #compile
ob_start(); ?>${src}<?php $src = ob_get_clean(); #compile
ob_start(); ?>${alt}<?php $alt = ob_get_clean(); #compile
ob_start(); ?>${context}<?php ob_get_clean(); #compile

$content = compact('src', 'alt');
$encoded = json_encode($content);

/** @var \Spiral\Pieces\Pieces $pieces */
$pieces = spiral(\Spiral\Pieces\Pieces::class);
$piece = $pieces->getPiece($id, $encoded, $this->view, $this->namespace);

$decoded = json_decode($piece->content, true);
?>

<?php if ($pieces->canEdit()): #compile ?>
    <img data-piece="image" data-id="${name}" src="<?= $decoded['src'] #compile  ?>"
         alt="<?= $decoded['alt'] #compile  ?>" node:attributes>
<?php else: #compile ?>
    <img src="<?= $decoded['src'] #compile  ?>" alt="<?= $decoded['alt'] #compile  ?>"
         node:attributes>
<?php endif; #compile ?>
