<?php
// source: C:\xampp\htdocs\sandbox\app\presenters/templates/Homepage/default.latte

use Latte\Runtime as LR;

class Template9494c03c03 extends Latte\Runtime\Template
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
		if (isset($this->params['post'])) trigger_error('Variable $post overwritten in foreach on line 19');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockBanner($_args)
	{
		
	}


	function blockTitle($_args)
	{
		extract($_args);
		?>    <h1><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.welcome")) ?></h1>
<?php
	}


	function blockContent($_args)
	{
		extract($_args);
?>

<div id="content">
<?php
		if ($user->loggedIn) {
			?>    <h2> <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.intro")) ?>  </h2>
    <div class="table-responsive">
    <table class="table table-hover table-bordered">
    <tr>
        <th><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.id_zaznam")) ?></th> <th><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.id_reader")) ?></th> <th><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.id_rfid")) ?></th> <th><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.timestamp")) ?></th> <th><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.spracovane")) ?></th>
    </tr>
<?php
			$iterations = 0;
			foreach ($posts as $post) {
?>
            <tr>
                <td><?php echo LR\Filters::escapeHtmlText($post->id) /* line 21 */ ?></td> 
                <td><?php echo LR\Filters::escapeHtmlText($post->citacka->id) /* line 22 */ ?></td> 
                <td><?php echo LR\Filters::escapeHtmlText($post->rfid_number) /* line 23 */ ?></td> 
                <td><?php echo LR\Filters::escapeHtmlText($post->timestamp) /* line 24 */ ?></td>
                <td>
<?php
				if ($post->spracovane) {
					?>                        <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.spracovane")) ?>

<?php
				}
?>
                </td>
            </tr>
<?php
				$iterations++;
			}
?>
    </table>
    </div>
<?php
		}
?>
</div>

<?php
	}

}