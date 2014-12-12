<?php
/**
 * The parent class for all Elgg Entities.
 *
 * An ElggEntity is one of the basic data models in Elgg.  It is the primary
 * means of storing and retrieving data from the database.  An ElggEntity
 * represents one row of the entities table.
 *
 * The ElggEntity class handles CRUD operations for the entities table.
 * ElggEntity should always be extended by another class to handle CRUD
 * operations on the type-specific table.
 *
 * ElggEntity uses magic methods for get and set, so any property that isn't
 * declared will be assumed to be metadata and written to the database
 * as metadata on the object.  All children classes must declare which
 * properties are columns of the type table or they will be assumed
 * to be metadata.  See ElggObject::initialise_entities() for examples.
 *
 * Core supports 4 types of entities: ElggObject, ElggUser, ElggGroup, and
 * ElggSite.
 *
 * @tip Most plugin authors will want to extend the ElggObject class
 * instead of this class.
 *
 * @package    Elgg.Core
 * @subpackage DataModel.Entities
 *
 * @property string $type           object, user, group, or site (read-only after save)
 * @property string $subtype        Further clarifies the nature of the entity (read-only after save)
 * @property int    $guid           The unique identifier for this entity (read only)
 * @property int    $owner_guid     The GUID of the creator of this entity
 * @property int    $container_guid The GUID of the entity containing this entity
 * @property int    $site_guid      The GUID of the website this entity is associated with
 * @property int    $access_id      Specifies the visibility level of this entity
 * @property int    $time_created   A UNIX timestamp of when the entity was created (read-only, set on first save)
 * @property int    $time_updated   A UNIX timestamp of when the entity was last updated (automatically updated on save)
 * @property-read string $enabled
 */
  class ElggCoverPhoto 

{


	/**
	 * Icon override, overrides the value of getIcon().
	 */
	protected $timelineicon_override;






	protected function initializeAttributes() {
parent::initializeAttributes();

$this->attributes['subtype'] = "timeline_avatar";
}



	 
	/* 
	 
	public function getType() {
		return $this->get('type');
	}

  */




	/**
	 * Get the URL for this entity's icon
	 *
	 * Plugins can register for the 'entity:icon:url', <type> plugin hook
	 * to customize the icon for an entity.
	 *
	 * @param string $size Size of the icon: tiny, small, medium, large
	 *
	 * @return string The URL
	 * @since 1.8.0
	 */
	public function getTimelineiconURL($size = 'kaka') {
		$size = elgg_strtolower($size);

		if (isset($this->timelineicon_override[$size])) {
			elgg_deprecated_notice("timelineicon_override on an individual entity is deprecated", 1.8);
			return $this->timelineicon_override[$size];
		}

		$type = $this->getType();
		$params = array(
			'entity' => $this,
			'size' => $size,
		);

		$url = elgg_trigger_plugin_hook('entity:icon:url', $type, $params, null);
		if ($url == null) {
			$url = "mod/timeline_avatar/graphics/timelineicons/default/$size.png";
		}

		return elgg_normalize_url($url);
	}

	/**
	 * Returns a URL for the entity's icon.
	 *
	 * @param string $size Either 'large', 'medium', 'small' or 'tiny'
	 *
	 * @return string The url or false if no url could be worked out.
	 * @deprecated Use getIconURL()
	 */
	public function getTimelineicon($size = 'kaka') {
		elgg_deprecated_notice("getTimelineicon() deprecated by getIconURL()", 1.8);
		return $this->getIconURL($size);
	}

	/**
	 * Set an icon override for an icon and size.
	 *
	 * @warning This override exists only for the life of the object.
	 *
	 * @param string $url  The url of the icon.
	 * @param string $size The size its for.
	 *
	 * @return bool
	 * @deprecated 1.8 See getIconURL() for the plugin hook to use
	 */
	public function setTimelineicon($url, $size = 'kaka') {
		elgg_deprecated_notice("timelineicon_override on an individual entity is deprecated", 1.8);

		$url = sanitise_string($url);
		$size = sanitise_string($size);

		if (!$this->timelineicon_override) {
			$this->timelineicon_override = array();
		}
		$this->timelineicon_override[$size] = $url;

		return true;
	}



	















}
