<?php #compile
/**
 * @var string $namespace
 * @var string $view
 * @var string $code
 * @var string $title
 * @var string $description
 * @var string $keywords
 */
$this->runtimeVariable('code', '${code}');
$this->runtimeVariable('title', '${title}');
$this->runtimeVariable('description', '${description}');
$this->runtimeVariable('keywords', '${keywords}');
$this->runtimeVariable('view', '${view}');
$this->runtimeVariable('namespace', '${namespace}');
$this->runtimeVariable('html', '${context}');
?><?php
/**
 * @var \Spiral\Views\EnvironmentInterface $environment
 */
$defaults = compact('title', 'description', 'keywords', 'html');

/** @var \Spiral\Pieces\Pieces $pieces */
$pieces = spiral(\Spiral\Pieces\Pieces::class);
$meta = $pieces->getMeta($namespace, $view, $code, $defaults);
?>
    <title><?= e($meta->title) ?></title>
    <meta name="description" content="<?= e($meta->description) ?>">
    <meta name="keywords" content="<?= e($meta->keywords) ?>">
<?= $meta->html ?>

<?php if ($environment->getValue('cms.editable')): ?>
    <script>window.metadata = <?= json_encode($meta) ?>;</script>
<?php endif; ?>