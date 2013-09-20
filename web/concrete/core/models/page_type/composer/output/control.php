<?
defined('C5_EXECUTE') or die("Access Denied.");
class Concrete5_Model_PageTypeComposerOutputControl extends Object {

	public function getPageTypeComposerOutputControlID() {return $this->ptComposerOutputControlID;}
	public function getPageTypeComposerFormLayoutSetControlID() {return $this->ptComposerFormLayoutSetControlID;}
	public function getPageTypeComposerFormLayoutSetID() {return $this->ptComposerFormLayoutSetID;}

	public static function add(PageTypeComposerFormLayoutSetControl $control, PageTemplate $pt) {

		$set = $control->getPageTypeComposerFormLayoutSetObject();
		$pagetype = $set->getPageTypeObject();

		$db = Loader::db();
		$db->Execute('insert into PageTypeComposerOutputControls (ptID, pTemplateID, ptComposerFormLayoutSetControlID) values (?, ?, ?)', array(
			$pagetype->getPageTypeID(), $pt->getPageTemplateID(), $control->getPageTypeComposerFormLayoutSetControlID()
		));
		$ptComposerOutputControlID = $db->Insert_ID();
		return PageTypeComposerOutputControl::getByID($ptComposerOutputControlID);
	}

	public static function getList(PageType $pt, PageTemplate $pt) {
		$db = Loader::db();
		// get all output controls for the particular page template.
		$ptComposerOutputControlIDs = $db->GetCol('select ptComposerOutputControlID from PageTypeComposerOutputControls where pTemplateID = ? and ptID = ? order by ptComposerOutputControlID asc', array(
			$pt->getPageTemplateID(), $pt->getPageTypeID()
		));
		$list = array();
		foreach($ptComposerOutputControlIDs as $ptComposerOutputControlID) {
			$cm = PageTypeComposerOutputControl::getByID($ptComposerOutputControlID);
			if (is_object($cm)) {
				$list[] = $cm;
			}
		}
		return $list;
	}

	public static function getByID($ptComposerOutputControlID) {
		$db = Loader::db();
		$r = $db->GetRow('select * from PageTypeComposerOutputControls where ptComposerOutputControlID = ?', array($ptComposerOutputControlID));
		if (is_array($r) && $r['ptComposerOutputControlID']) {
			$cm = new PageTypeComposerOutputControl;
			$cm->setPropertiesFromArray($r);
			return $cm;
		}
	}

	public static function getByPageTypeComposerFormLayoutSetControl(PageTemplate $pt, PageTypeComposerFormLayoutSetControl $control) {
		$db = Loader::db();
		$ptComposerOutputControlID = $db->GetOne('select ptComposerOutputControlID from PageTypeComposerOutputControls where pTemplateID = ? and ptComposerFormLayoutSetControlID = ?', array($pt->getPageTemplateID(), $control->getPageTypeComposerFormLayoutSetControlID()));
		if ($ptComposerOutputControlID) {
			return PageTypeComposerOutputControl::getByID($ptComposerOutputControlID);
		}
	}

	public function delete() {
		$db = Loader::db();
		$db->Execute('delete from PageTypeComposerOutputControls where ptComposerOutputControlID = ?', array($this->ptComposerOutputControlID));
	}

	public function getPageTypeComposerControlOutputLabel() {
		$control = PageTypeComposerFormLayoutSetControl::getByID($this->ptComposerFormLayoutSetControlID);
		return $control->getPageTypeComposerControlLabel();
	}

}