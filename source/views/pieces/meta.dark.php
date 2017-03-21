<?php #compile
/* @var \Spiral\Views\DynamicEnvironment $environment */
ob_start(); ?>${title}<?php $title = ob_get_clean(); #compile
ob_start(); ?>${description}<?php $description = ob_get_clean(); #compile
ob_start(); ?>${keywords}<?php $keywords = ob_get_clean(); #compile
ob_start(); ?>${context}<?php $html = ob_get_clean(); #compile

$defaults = compact('title', 'description', 'keywords', 'html');

/** @var \Spiral\Pieces\Pieces $pieces */
$pieces = spiral(\Spiral\Pieces\Pieces::class);
$meta = $pieces->getMeta($this->namespace, $this->view, "static", $defaults);
?>

<meta name="description" content="<?= e($meta->description) #compile ?>">
<meta name="keywords" content="<?= e($meta->keywords) #compile ?>">
<?= $meta->html #compile ?>

<title><?= e($meta->title) #compile ?></title>

<?php if ($environment->getValue('cms.editable')): #compile ?>
    <script>
        window.metadata = <?= json_encode($meta) #compile ?>;
    </script>
<?php endif; #compile ?>