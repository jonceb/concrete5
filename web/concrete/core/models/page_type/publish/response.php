<?
defined('C5_EXECUTE') or die("Access Denied.");
class Concrete5_Model_PageTypePublishResponse extends Object {

	public $error = false;
	public $messages = array();
	public $outputControls = array();
	public $pDraftID;
	public $saveURL;
	public $redirectURL;
	public $pDraftSaveStatus;
	public $publishURL;
	public $discardURL;
	public $viewURL;

	public function __construct($e = false) {
		if ($e instanceof ValidationErrorHelper && $e->has()) {
			$this->error = true;
			$this->messages = $e->getList();
		}
		$this->time = date('F d, Y g:i A');
	}

	public function setDraftSaveStatus($pDraftSaveStatus) {
		$this->pDraftSaveStatus = $pDraftSaveStatus;
	}

	public function setRedirectURL($url) {
		$this->redirectURL = $url;
	}

	public function getRedirectURL() {
		return $this->redirectURL;
	}

	public function setOutputControls($outputControls) {
		$this->outputControls = $outputControls;
	}

	public function setPageDraft(PageDraft $pDraft) {
		$this->pDraftID = $pDraft->getPageDraftID();
	}

	public function setSaveURL($saveURL) {
		$this->saveURL = $saveURL;
	}
	
	public function setDiscardURL($discardURL) {
		$this->discardURL = $discardURL;
	}

	public function setViewURL($viewURL) {
		$this->viewURL = $viewURL;
	}
	
	public function setPublishURL($publishURL) {
		$this->publishURL = $publishURL;
	}



}