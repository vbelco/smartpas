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
        <th><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.name")) ?></th> <th><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.prichod")) ?></th> <th><?php
			echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.odchod")) ?></th>
    </tr>
<?php
			$iterations = 0;
			foreach ($posts as $post) {
?>
            <tr>
                <td> <?php echo LR\Filters::escapeHtmlText($post->people->meno) /* line 21 */ ?> </td>
                <td> <?php
				if ($post->prichod_timestamp) {
?>

                        <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->date, $post->prichod_timestamp, "d. m. Y G:i:s")) /* line 23 */ ?>

<?php
				}
				else {
					?>                        <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.nedefinovane")) ?>

<?php
				}
?>
                </td>
                <td> <?php
				if ($post->odchod_timestamp) {
?>

                        <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->date, $post->odchod_timestamp, "d. m. Y G:i:s")) /* line 29 */ ?>

<?php
				}
				else {
					?>                        <?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->translate, "ui.nedefinovane")) ?>

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
