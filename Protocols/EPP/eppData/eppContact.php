<?php
namespace Metaregistrar\EPP;
/**
 * The SIDN EPP Contact Info Object
 *
 * This will hold the complete contact info SIDN can receive and give you
 *
 */

class eppContact {

    #
    # These status values cannot be set, only viewed
    #
    const STATUS_OK = 'ok';
    const STATUS_SERVER_DELETE_PROHIBITED = 'serverDeleteProhibited';
    const STATUS_SERVER_UPDATE_PROHIBITED = 'serverUpdateProhibited';
    const STATUS_SERVER_TRANSFER_PROHIBITED = 'serverTransferProhibited';
    const STATUS_LINKED = 'linked';
    const STATUS_PENDING_CREATE = 'pendingCreate';
    const STATUS_PENDING_DELETE = 'pendingDelete';
    const STATUS_PENDING_TRANSFER = 'pendingTransfer';
    const STATUS_PENDING_UPDATE = 'pendingUpdate';

    #
    # These status values can be set
    #
    const STATUS_CLIENT_DELETE_PROHIBITED = 'clientDeleteProhibited';
    const STATUS_CLIENT_UPDATE_PROHIBITED = 'clientUpdateProhibited';
    const STATUS_CLIENT_TRANSFER_PROHIBITED = 'clientTransferProhibited';

    private $postalInfo = array();
    private $voice=null;
    private $fax=null;
    private $email=null;
    private $password=null;
    private $status=null;
    private $type = 'loc';
    private $disclose = null;


    /**
     *
     * @param eppContactPostalInfo $postalInfo
     * @param string $email
     * @param string $voice
     * @param string $fax
     * @param string $password
     * @param string $status
     * @return void
     */
    public function __construct($postalInfo = null, $email = null, $voice = null, $fax = null, $password = null, $status = null) {
        foreach ($postalInfo as $pi) {
            if ($pi instanceof eppContactPostalInfo) {
                $this->addPostalInfo($pi);
            }
        }
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setVoice($voice);
        $this->setFax($fax);
        $this->setStatus($status);
    }

    public function setDisclose($disclose) {
        $this->disclose = $disclose;
    }

    public function getDisclose() {
        return $this->disclose;
    }


    public function setType($type) {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    /**
     * Add postal info to this contact
     * @param eppContactPostalInfo $postalInfo
     * @return void
     */
    public function addPostalInfo(eppContactPostalInfo $postalInfo) {
        if (count($this->postalInfo) < 2) {
            $this->postalInfo[count($this->postalInfo)] = $postalInfo;
        } else {
            throw new eppException('Cannot add more than 3 postal information blocks to a contact');
        }
    }

    /**
     * Gets the total number of postalinfo objects this contact holds
     * @return int
     */
    public function getPostalInfoLength() {
        return count($this->postalInfo);
    }

    /**
     * Retrieve a postalInfo object by number
     *
     * @param int $line
     * @return eppContactPostalInfo
     */
    public function getPostalInfo($line) {
        if ($this->postalInfo[$line]) {
            return $this->postalInfo[$line];
        } else {
            return null;
        }
    }

    /**
     * Sets the status
     * @param string $status
     */
    public function setStatus($status) {
        if (is_array($status)) {
            $this->status = $status;
        } else {
            if ($status != null) {
                $this->status[] = $status;
            }
        }
    }

    /**
     * Sets the status
     * @return string status
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Sets the email address
     * @param string $email
     * @return void
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Gets the email address
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Sets the password
     *
     * **NOTE** This is not used by at the moment, but they do require it to be given
     * @param string $password
     * @return void
     */

    public function setPassword($password) {
        $this->password = $password;

    }

    /**
     * Gets the password
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Sets the phone number
     * @param int $voice
     * @return void
     */
    public function setVoice($voice) {
        $this->voice = $this->validatePhoneNumber($voice);
    }

    /**
     * Gets the phone number
     * @return string
     */
    public function getVoice() {
        return $this->voice;
    }

    /**
     * Sets the fax number
     * @param int $fax
     * @return void
     */
    public function setFax($fax) {
        $this->fax = $this->validatePhoneNumber($fax);
    }

    /**
     * Gets the fax number
     * @return string
     */
    public function getFax() {
        return $this->fax;
    }

    /**
     * Formats the phone number according to SIDN formatting rules
     * @param int $number
     * @return string
     */
    private function validatePhoneNumber($number) {
        //Format the phone number according to EPP formatting rules.
        if (!strlen($number)) {
            return null;
        }
        if ($number{0} != '+') {
            throw new eppException('Phone number ' . $number . ' is not valid for EPP. Valid format is +cc.nnnnnnnnnnn');
        }
        if (strpos($number, '.') === false) {
            throw new eppException('Phone number ' . $number . ' is not valid for EPP. Valid format is +cc.nnnnnnnnnnn');
        }
        return $number;
    }

    /**
     *
     * @return string ContactId
     */
    public function generateContactId() {
#        $contactid = 3001;
        #       return sprintf("MRG%05d",$contactid);
        return uniqid('MRG');
    }
}