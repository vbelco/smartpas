<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/Citacka/default.latte

use Latte\Runtime as LR;

class Template2dba929ec5 extends Latte\Runtime\Template
{
	public $blocks = [
		'banner' => 'blockBanner',
		'title' => 'blockTitle',
		'content' => 'blockContent',
	];

	public $blockTypes = [
		'banner' => 'html',
		'title' => 'html',
		'content' => 'html',
	];


	function main()
	{
		extract($this->params);
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('banner', get_defined_vars());
?>

<?php
		$this->renderBlock('title', get_defined_vars());
?>


<?php
		$this->renderBlock('content', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (isset($this->params['post'])) trigger_error('Variable $post overwritten in foreach on line 37');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockBanner($_args)
	{
		
	}


	function blockTitle($_args)
	{
		extract($_args);
		?>    <h1><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.management_citaciek")) ?></h1>
<?php
	}


	function blockContent($_args)
	{
		extract($_args);
		if ($user->loggedIn) {
			?>    <script src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 13 */ ?>/js/my_mqtt.js" charset="UTF-8"></script>
    <script>
        $(document).ready(function() {
            MQTTconnect();
        });
        
    </script>
    
<div>
    <ul class="nav nav-pills">
        <li class="active"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Citacka:default")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.citacka.prehlad")) ?></a></li>
        <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Citacka:pridaj")) ?>"><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "menu.citacka.pridaj")) ?></a></li>
    </ul>
</div>

<div class="table-responsive">
<table class=" table table-hover" id="citacky-tabulka">
    <tr>
        <th><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.id_reader")) ?></th>
        <th><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.name_reader")) ?></th>
        <th></th>
        <th><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.action")) ?></th>
    </tr>
<?php
			$iterations = 0;
			foreach ($posts as $post) {
?>
            <tr>
                <td><?php echo LR\Filters::escapeHtmlText($post->id) /* line 39 */ ?></td> 
                <td><?php echo LR\Filters::escapeHtmlText($post->name) /* line 40 */ ?></td> 
                <td id="cell_kontrola_<?php echo LR\Filters::escapeHtmlAttr($post->id) /* line 41 */ ?>"><div id="kontrola_<?php
				echo LR\Filters::escapeHtmlAttr($post->id) /* line 41 */ ?>"> offline </div></td>
                <td>
                    <a class="btn btn-default" role="button" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("edituj", [$post->id])) ?>"><?php
				echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.form.edit")) ?></a> </td>     
            </tr>
    <script>
        Prva_kontrola_dostupnosti_citacky( <?php echo LR\Filters::escapeJs($post->id) /* line 47 */ ?> );
        setInterval( 'Kontrola_dostupnosti_citacky( <?php echo LR\Filters::escapeJs($post->id) /* line 48 */ ?> )'  , 10000 );
    </script>
<?php
				$iterations++;
			}
?>
</table>
</div>
<?php
		}
?> 
<?php
	}

}
