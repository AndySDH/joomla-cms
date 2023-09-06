<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Language\Text;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

$params             = $this->item->params;

$displayGroups      = $params->get('show_user_custom_fields');
$userFieldGroups    = [];
?>

<?php if (!$displayGroups || !$this->contactUser) : ?>
    <?php return; ?>
<?php endif; ?>

<?php foreach ($this->contactUser->jcfields as $field) : ?>
    <?php if ($field->value && (in_array('-1', $displayGroups) || in_array($field->group_id, $displayGroups))) : ?>
        <?php $userFieldGroups[$field->group_id][] = $field; ?>
    <?php endif; ?>
<?php endforeach; ?>

<?php foreach ($userFieldGroups as $group_id => $fields) : ?>

		<?php
		
		$output = [];
		
		foreach ($fields as $field)
		{
			$class   = $field->params->get('render_class');
			$layout  = $field->params->get('layout', 'render');
			$content = FieldsHelper::render('com_users.user', 'field.' . $layout, ['field' => $field]);
			
			// If the content is empty do nothing
			if (trim($content) === '')
			{
				continue;
			}
			$output[] = '<li class="field-entry ' . $class . '">' . $content . '</li>';
		}
		// If the group is empty don't output it
		if (empty($output))
		{
			continue;
		}
		
		?>
	
    <?php $alias = ApplicationHelper::stringURLSafe($field->group_title); ?>
    <?php echo '<h2>' . ($field->group_title ?: Text::_('COM_CONTACT_USER_FIELDS')) . '</h2>'; ?>

    <div class="com-contact__user-fields contact-profile" id="user-custom-fields-<?php echo $alias; ?>">
		<ul class="fields-container">
			<?php echo implode("\n", $output); ?>
        <ul>
    </div>
<?php endforeach; ?>
