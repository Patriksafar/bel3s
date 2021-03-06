<?php
/**
 * @access public
 * @author Kryštof Košut
 */

namespace database;
use database;
use database\jidlo;
class kategorie {
	private $_mysqli;

	public $nazev;
	public $id;
	public $url;
	public $background;
	public $topmenu;
	public $icon;
	public $visible;
	//public $position;


	 public function __construct()
 	 {
 	 	$this->_mysqli = new \database\database();
 	 }

	 /**
		* Získá všechy kategorie uložené v databázi
		* @param limit (nepovinny)
		* @return array vsech kategorie
		*/
	 public function getAllKategorie($where = NULL, $limit = NULL)
   {
		 $query = "SELECT * FROM kategorie";
		 if ($limit != NULL) {
			 $query .= " LIMIT ".$limit;
		 } if ($where != NULL){
		     $query .= " WHERE ".$where;
		 }
		 $result = $this->_mysqli->get_results($query);
		return $result;
   }

   public function setUpKategorie($id){
        $kategorie = $this->getKategorieById($id);
        $this->id = $id;
        $this->nazev = $kategorie['nazev'];
        $this->url = $kategorie['url'];
        $this->background = $kategorie['background'];
        $this->icon = $kategorie['icon'];
        $this->topmenu = $kategorie['topmenu'];
        $this->visible = $kategorie['visible'];

        unset($kategorie);
   }

   public function getKategorieById($id){
       $id = htmlspecialchars($id);
       $query = "SELECT * FROM kategorie WHERE id = '{$id}'";
       $result = $this->_mysqli->get_row( $query );
       return $result;
   }

    public function getKategorieUrlById($id){
      $query = "SELECT url FROM kategorie WHERE id = $id";
      $result = $this->_mysqli->get_row($query);
      return $result;
   }
    /**
     * @param $id integer
     * @return bool
     */
    public function deleteKategorie($id){
       $delete = array("id" => $id );

       $deleted = $this->_mysqli->delete("kategorie", $delete);
       if ($deleted) {
           return TRUE;
       } else {
           return FALSE;
       }
   }

    /**
     * @param $update array
     * @param $where array
     * @return bool
     */
    public function updateKategorie($update, $where, $originalURL = '')
   {
       if ($originalURL != ''){
           $menuItemClass = new jidlo($this->_mysqli);
           $menuItem_update = [
               'kategorie' => (string)$update['url']
           ];

           $menuItem_where = [
                'kategorie' => (string)$originalURL
           ];
           $menuItem_result = $menuItemClass->editJidlo($menuItem_update, $menuItem_where);
       }
       $result 	= $this->_mysqli->update( 'kategorie', $update, $where, 1 );

       if ($result AND $menuItem_result) {
           return true;
       } else {
           return false;
       }
   }

    /**
     * @param $insert array
     * @return bool
     */
    public function addKategorie($insert)
    {
        $add = $this->_mysqli->insert("kategorie", $insert);
        if ($add) {
            $lid = $this->_mysqli->lastid();
            return $lid;
        }
        else {
            return FALSE;
        }
    }

    /**
     * @param $url string
     * @return array or false
     */
    public function getKategorieByURL($url){
            $url = htmlspecialchars($url);
            $query = "SELECT * FROM kategorie WHERE url = '{$url}'";
            $result = $this->_mysqli->get_row( $query );
        return $result;
    }

    public function remove_accents( $text) { $trans = array( 'À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A',
        'Ç'=>'C','È'=>'E', 'É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N', 'Ò'=>'O','Ó'=>'O',
        'Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U', 'Û'=>'U','Ü'=>'U','Ý'=>'Y','à'=>'a','á'=>'a','â'=>'a',
        'ã'=>'a','ä'=>'a', 'å'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i', 'í' => 'i', 'î'=>'i','ï'=>'i',
        'ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o', 'ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u','ý'=>'y',
        'ÿ'=>'y','Ā'=>'A', 'ā'=>'a','Ă'=>'A','ă'=>'a','Ą'=>'A','ą'=>'a','Ć'=>'C','ć'=>'c','Ĉ'=>'C', 'ĉ'=>'c','Ċ'=>'C',
        'ċ'=>'c','Č'=>'C','č'=>'c','Ď'=>'D','ď'=>'d','Đ'=>'D', 'đ'=>'d','Ē'=>'E','ē'=>'e','Ĕ'=>'E','ĕ'=>'e','Ė'=>'E',
        'ė'=>'e','Ę'=>'E', 'ę'=>'e','Ě'=>'E','ě'=>'e','Ĝ'=>'G','ĝ'=>'g','Ğ'=>'G','ğ'=>'g','Ġ'=>'G', 'ġ'=>'g','Ģ'=>'G',
        'ģ'=>'g','Ĥ'=>'H','ĥ'=>'h','Ħ'=>'H','ħ'=>'h','Ĩ'=>'I', 'ĩ'=>'i','Ī'=>'I','ī'=>'i','Ĭ'=>'I','ĭ'=>'i','Į'=>'I',
        'į'=>'i','İ'=>'I', 'ı'=>'i','Ĵ'=>'J','ĵ'=>'j','Ķ'=>'K','ķ'=>'k','Ĺ'=>'L','ĺ'=>'l','Ļ'=>'L', 'ļ'=>'l','Ľ'=>'L',
        'ľ'=>'l','Ŀ'=>'L','ŀ'=>'l','Ł'=>'L','ł'=>'l','Ń'=>'N', 'ń'=>'n','Ņ'=>'N','ņ'=>'n','Ň'=>'N','ň'=>'n','ŉ'=>'n',
        'Ō'=>'O','ō'=>'o', 'Ŏ'=>'O','ŏ'=>'o','Ő'=>'O','ő'=>'o','Ŕ'=>'R','ŕ'=>'r','Ŗ'=>'R','ŗ'=>'r', 'Ř'=>'R','ř'=>'r',
        'Ś'=>'S','ś'=>'s','Ŝ'=>'S','ŝ'=>'s','Ş'=>'S','ş'=>'s', 'Š'=>'S','š'=>'s','Ţ'=>'T','ţ'=>'t','Ť'=>'T','ť'=>'t',
        'Ŧ'=>'T','ŧ'=>'t', 'Ũ'=>'U','ũ'=>'u','Ū'=>'U','ū'=>'u','Ŭ'=>'U','ŭ'=>'u','Ů'=>'U','ů'=>'u', 'Ű'=>'U','ű'=>'u',
        'Ų'=>'U','ų'=>'u','Ŵ'=>'W','ŵ'=>'w','Ŷ'=>'Y','ŷ'=>'y', 'Ÿ'=>'Y','Ź'=>'Z','ź'=>'z','Ż'=>'Z','ż'=>'z','Ž'=>'Z',
        'ž'=>'z','ƀ'=>'b', 'Ɓ'=>'B','Ƃ'=>'B','ƃ'=>'b','Ƈ'=>'C','ƈ'=>'c','Ɗ'=>'D','Ƌ'=>'D','ƌ'=>'d', 'Ƒ'=>'F','ƒ'=>'f',
        'Ɠ'=>'G','Ɨ'=>'I','Ƙ'=>'K','ƙ'=>'k','ƚ'=>'l','Ɲ'=>'N', 'ƞ'=>'n','Ɵ'=>'O','Ơ'=>'O','ơ'=>'o','Ƥ'=>'P','ƥ'=>'p',
        'ƫ'=>'t','Ƭ'=>'T', 'ƭ'=>'t','Ʈ'=>'T','Ư'=>'U','ư'=>'u','Ʋ'=>'V','Ƴ'=>'Y','ƴ'=>'y','Ƶ'=>'Z', 'ƶ'=>'z','ǅ'=>'D',
        'ǈ'=>'L','ǋ'=>'N','Ǎ'=>'A','ǎ'=>'a','Ǐ'=>'I','ǐ'=>'i', 'Ǒ'=>'O','ǒ'=>'o','Ǔ'=>'U','ǔ'=>'u','Ǖ'=>'U','ǖ'=>'u',
        'Ǘ'=>'U','ǘ'=>'u', 'Ǚ'=>'U','ǚ'=>'u','Ǜ'=>'U','ǜ'=>'u','Ǟ'=>'A','ǟ'=>'a','Ǡ'=>'A','ǡ'=>'a', 'Ǥ'=>'G','ǥ'=>'g',
        'Ǧ'=>'G','ǧ'=>'g','Ǩ'=>'K','ǩ'=>'k','Ǫ'=>'O','ǫ'=>'o', 'Ǭ'=>'O','ǭ'=>'o','ǰ'=>'j','ǲ'=>'D','Ǵ'=>'G','ǵ'=>'g',
        'Ǹ'=>'N','ǹ'=>'n', 'Ǻ'=>'A','ǻ'=>'a','Ǿ'=>'O','ǿ'=>'o','Ȁ'=>'A','ȁ'=>'a','Ȃ'=>'A','ȃ'=>'a', 'Ȅ'=>'E','ȅ'=>'e',
        'Ȇ'=>'E','ȇ'=>'e','Ȉ'=>'I','ȉ'=>'i','Ȋ'=>'I','ȋ'=>'i', 'Ȍ'=>'O','ȍ'=>'o','Ȏ'=>'O','ȏ'=>'o','Ȑ'=>'R','ȑ'=>'r',
        'Ȓ'=>'R','ȓ'=>'r', 'Ȕ'=>'U','ȕ'=>'u','Ȗ'=>'U','ȗ'=>'u','Ș'=>'S','ș'=>'s','Ț'=>'T','ț'=>'t', 'Ȟ'=>'H','ȟ'=>'h',
        'Ƞ'=>'N','ȡ'=>'d','Ȥ'=>'Z','ȥ'=>'z','Ȧ'=>'A','ȧ'=>'a', 'Ȩ'=>'E','ȩ'=>'e','Ȫ'=>'O','ȫ'=>'o','Ȭ'=>'O','ȭ'=>'o',
        'Ȯ'=>'O','ȯ'=>'o', 'Ȱ'=>'O','ȱ'=>'o','Ȳ'=>'Y','ȳ'=>'y','ȴ'=>'l','ȵ'=>'n','ȶ'=>'t','ȷ'=>'j', 'Ⱥ'=>'A','Ȼ'=>'C',
        'ȼ'=>'c','Ƚ'=>'L','Ⱦ'=>'T','ȿ'=>'s','ɀ'=>'z','Ƀ'=>'B', 'Ʉ'=>'U','Ɇ'=>'E','ɇ'=>'e','Ɉ'=>'J','ɉ'=>'j','ɋ'=>'q',
        'Ɍ'=>'R','ɍ'=>'r', 'Ɏ'=>'Y','ɏ'=>'y','ɓ'=>'b','ɕ'=>'c','ɖ'=>'d','ɗ'=>'d','ɟ'=>'j','ɠ'=>'g', 'ɦ'=>'h','ɨ'=>'i',
        'ɫ'=>'l','ɬ'=>'l','ɭ'=>'l','ɱ'=>'m','ɲ'=>'n','ɳ'=>'n', 'ɵ'=>'o','ɼ'=>'r','ɽ'=>'r','ɾ'=>'r','ʂ'=>'s','ʄ'=>'j',
        'ʈ'=>'t','ʉ'=>'u', 'ʋ'=>'v','ʐ'=>'z','ʑ'=>'z','ʝ'=>'j','ʠ'=>'q','ͣ'=>'a','ͤ'=>'e','ͥ'=>'i', 'ͦ'=>'o','ͧ'=>'u',
        'ͨ'=>'c','ͩ'=>'d','ͪ'=>'h','ͫ'=>'m','ͬ'=>'r','ͭ'=>'t', 'ͮ'=>'v','ͯ'=>'x','ᵢ'=>'i','ᵣ'=>'r','ᵤ'=>'u','ᵥ'=>'v',
        'ᵬ'=>'b','ᵭ'=>'d', 'ᵮ'=>'f','ᵯ'=>'m','ᵰ'=>'n','ᵱ'=>'p','ᵲ'=>'r','ᵳ'=>'r','ᵴ'=>'s','ᵵ'=>'t', 'ᵶ'=>'z','ᵻ'=>'i',
        'ᵽ'=>'p','ᵾ'=>'u','ᶀ'=>'b','ᶁ'=>'d','ᶂ'=>'f','ᶃ'=>'g', 'ᶄ'=>'k','ᶅ'=>'l','ᶆ'=>'m','ᶇ'=>'n','ᶈ'=>'p','ᶉ'=>'r',
        'ᶊ'=>'s','ᶌ'=>'v', 'ᶍ'=>'x','ᶎ'=>'z','ᶏ'=>'a','ᶑ'=>'d','ᶒ'=>'e','ᶖ'=>'i','ᶙ'=>'u','᷊'=>'r', 'ᷗ'=>'c','ᷚ'=>'g',
        'ᷜ'=>'k','ᷝ'=>'l','ᷠ'=>'n','ᷣ'=>'r','ᷤ'=>'s','ᷦ'=>'z', 'Ḁ'=>'A','ḁ'=>'a','Ḃ'=>'B','ḃ'=>'b','Ḅ'=>'B','ḅ'=>'b',
        'Ḇ'=>'B','ḇ'=>'b', 'Ḉ'=>'C','ḉ'=>'c','Ḋ'=>'D','ḋ'=>'d','Ḍ'=>'D','ḍ'=>'d','Ḏ'=>'D','ḏ'=>'d', 'Ḑ'=>'D','ḑ'=>'d',
        'Ḓ'=>'D','ḓ'=>'d','Ḕ'=>'E','ḕ'=>'e','Ḗ'=>'E','ḗ'=>'e', 'Ḙ'=>'E','ḙ'=>'e','Ḛ'=>'E','ḛ'=>'e','Ḝ'=>'E','ḝ'=>'e',
        'Ḟ'=>'F','ḟ'=>'f', 'Ḡ'=>'G','ḡ'=>'g','Ḣ'=>'H','ḣ'=>'h','Ḥ'=>'H','ḥ'=>'h','Ḧ'=>'H','ḧ'=>'h', 'Ḩ'=>'H','ḩ'=>'h',
        'Ḫ'=>'H','ḫ'=>'h','Ḭ'=>'I','ḭ'=>'i','Ḯ'=>'I','ḯ'=>'i', 'Ḱ'=>'K','ḱ'=>'k','Ḳ'=>'K','ḳ'=>'k','Ḵ'=>'K','ḵ'=>'k',
        'Ḷ'=>'L','ḷ'=>'l', 'Ḹ'=>'L','ḹ'=>'l','Ḻ'=>'L','ḻ'=>'l','Ḽ'=>'L','ḽ'=>'l','Ḿ'=>'M','ḿ'=>'m', 'Ṁ'=>'M','ṁ'=>'m',
        'Ṃ'=>'M','ṃ'=>'m','Ṅ'=>'N','ṅ'=>'n','Ṇ'=>'N','ṇ'=>'n', 'Ṉ'=>'N','ṉ'=>'n','Ṋ'=>'N','ṋ'=>'n','Ṍ'=>'O','ṍ'=>'o',
        'Ṏ'=>'O','ṏ'=>'o', 'Ṑ'=>'O','ṑ'=>'o','Ṓ'=>'O','ṓ'=>'o','Ṕ'=>'P','ṕ'=>'p','Ṗ'=>'P','ṗ'=>'p', 'Ṙ'=>'R','ṙ'=>'r',
        'Ṛ'=>'R','ṛ'=>'r','Ṝ'=>'R','ṝ'=>'r','Ṟ'=>'R','ṟ'=>'r', 'Ṡ'=>'S','ṡ'=>'s','Ṣ'=>'S','ṣ'=>'s','Ṥ'=>'S','ṥ'=>'s',
        'Ṧ'=>'S','ṧ'=>'s', 'Ṩ'=>'S','ṩ'=>'s','Ṫ'=>'T','ṫ'=>'t','Ṭ'=>'T','ṭ'=>'t','Ṯ'=>'T','ṯ'=>'t', 'Ṱ'=>'T','ṱ'=>'t',
        'Ṳ'=>'U','ṳ'=>'u','Ṵ'=>'U','ṵ'=>'u','Ṷ'=>'U','ṷ'=>'u', 'Ṹ'=>'U','ṹ'=>'u','Ṻ'=>'U','ṻ'=>'u','Ṽ'=>'V','ṽ'=>'v',
        'Ṿ'=>'V','ṿ'=>'v', 'Ẁ'=>'W','ẁ'=>'w','Ẃ'=>'W','ẃ'=>'w','Ẅ'=>'W','ẅ'=>'w','Ẇ'=>'W','ẇ'=>'w', 'Ẉ'=>'W','ẉ'=>'w',
        'Ẋ'=>'X','ẋ'=>'x','Ẍ'=>'X','ẍ'=>'x','Ẏ'=>'Y','ẏ'=>'y', 'Ẑ'=>'Z','ẑ'=>'z','Ẓ'=>'Z','ẓ'=>'z','Ẕ'=>'Z','ẕ'=>'z',
        'ẖ'=>'h','ẗ'=>'t', 'ẘ'=>'w','ẙ'=>'y','ẚ'=>'a','Ạ'=>'A','ạ'=>'a','Ả'=>'A','ả'=>'a','Ấ'=>'A', 'ấ'=>'a','Ầ'=>'A',
        'ầ'=>'a','Ẩ'=>'A','ẩ'=>'a','Ẫ'=>'A','ẫ'=>'a','Ậ'=>'A', 'ậ'=>'a','Ắ'=>'A','ắ'=>'a','Ằ'=>'A','ằ'=>'a','Ẳ'=>'A',
        'ẳ'=>'a','Ẵ'=>'A', 'ẵ'=>'a','Ặ'=>'A','ặ'=>'a','Ẹ'=>'E','ẹ'=>'e','Ẻ'=>'E','ẻ'=>'e','Ẽ'=>'E', 'ẽ'=>'e','Ế'=>'E',
        'ế'=>'e','Ề'=>'E','ề'=>'e','Ể'=>'E','ể'=>'e','Ễ'=>'E', 'ễ'=>'e','Ệ'=>'E','ệ'=>'e','Ỉ'=>'I','ỉ'=>'i','Ị'=>'I',
        'ị'=>'i','Ọ'=>'O', 'ọ'=>'o','Ỏ'=>'O','ỏ'=>'o','Ố'=>'O','ố'=>'o','Ồ'=>'O','ồ'=>'o','Ổ'=>'O', 'ổ'=>'o','Ỗ'=>'O',
        'ỗ'=>'o','Ộ'=>'O','ộ'=>'o','Ớ'=>'O','ớ'=>'o','Ờ'=>'O', 'ờ'=>'o','Ở'=>'O','ở'=>'o','Ỡ'=>'O','ỡ'=>'o','Ợ'=>'O',
        'ợ'=>'o','Ụ'=>'U', 'ụ'=>'u','Ủ'=>'U','ủ'=>'u','Ứ'=>'U','ứ'=>'u','Ừ'=>'U','ừ'=>'u','Ử'=>'U', 'ử'=>'u','Ữ'=>'U',
        'ữ'=>'u','Ự'=>'U','ự'=>'u','Ỳ'=>'Y','ỳ'=>'y','Ỵ'=>'Y', 'ỵ'=>'y','Ỷ'=>'Y','ỷ'=>'y','Ỹ'=>'Y','ỹ'=>'y','Ỿ'=>'Y',
        'ỿ'=>'y','ⁱ'=>'i', 'ⁿ'=>'n','ₐ'=>'a','ₑ'=>'e','ₒ'=>'o','ₓ'=>'x','⒜'=>'a','⒝'=>'b','⒞'=>'c', '⒟'=>'d','⒠'=>'e',
        '⒡'=>'f','⒢'=>'g','⒣'=>'h','⒤'=>'i','⒥'=>'j','⒦'=>'k', '⒧'=>'l','⒨'=>'m','⒩'=>'n','⒪'=>'o','⒫'=>'p','⒬'=>'q',
        '⒭'=>'r','⒮'=>'s', '⒯'=>'t','⒰'=>'u','⒱'=>'v','⒲'=>'w','⒳'=>'x','⒴'=>'y','⒵'=>'z','Ⓐ'=>'A', 'Ⓑ'=>'B',
        'Ⓒ'=>'C','Ⓓ'=>'D','Ⓔ'=>'E','Ⓕ'=>'F','Ⓖ'=>'G','Ⓗ'=>'H','Ⓘ'=>'I', 'Ⓙ'=>'J','Ⓚ'=>'K','Ⓛ'=>'L','Ⓜ'=>'M',
        'Ⓝ'=>'N','Ⓞ'=>'O','Ⓟ'=>'P','Ⓠ'=>'Q', 'Ⓡ'=>'R','Ⓢ'=>'S','Ⓣ'=>'T','Ⓤ'=>'U','Ⓥ'=>'V','Ⓦ'=>'W','Ⓧ'=>'X',
        'Ⓨ'=>'Y', 'Ⓩ'=>'Z','ⓐ'=>'a','ⓑ'=>'b','ⓒ'=>'c','ⓓ'=>'d','ⓔ'=>'e','ⓕ'=>'f','ⓖ'=>'g', 'ⓗ'=>'h','ⓘ'=>'i',
        'ⓙ'=>'j','ⓚ'=>'k','ⓛ'=>'l','ⓜ'=>'m','ⓝ'=>'n','ⓞ'=>'o', 'ⓟ'=>'p','ⓠ'=>'q','ⓡ'=>'r','ⓢ'=>'s','ⓣ'=>'t',
        'ⓤ'=>'u','ⓥ'=>'v','ⓦ'=>'w', 'ⓧ'=>'x','ⓨ'=>'y','ⓩ'=>'z','Ⱡ'=>'L','ⱡ'=>'l','Ɫ'=>'L','Ᵽ'=>'P','Ɽ'=>'R', 'ⱥ'=>'a',
        'ⱦ'=>'t','Ⱨ'=>'H','ⱨ'=>'h','Ⱪ'=>'K','ⱪ'=>'k','Ⱬ'=>'Z','ⱬ'=>'z', 'Ɱ'=>'M','ⱱ'=>'v','Ⱳ'=>'W','ⱳ'=>'w','ⱴ'=>'v',
        'ⱸ'=>'e','ⱺ'=>'o','ⱼ'=>'j', 'Ꝁ'=>'K','ꝁ'=>'k','Ꝃ'=>'K','ꝃ'=>'k','Ꝅ'=>'K','ꝅ'=>'k','Ꝉ'=>'L','ꝉ'=>'l', 'Ꝋ'=>'O',
        'ꝋ'=>'o','Ꝍ'=>'O','ꝍ'=>'o','Ꝑ'=>'P','ꝑ'=>'p','Ꝓ'=>'P','ꝓ'=>'p', 'Ꝕ'=>'P','ꝕ'=>'p','Ꝗ'=>'Q','ꝗ'=>'q','Ꝙ'=>'Q',
        'ꝙ'=>'q','Ꝛ'=>'R','ꝛ'=>'r', 'Ꝟ'=>'V','ꝟ'=>'v','Ａ'=>'A','Ｂ'=>'B','Ｃ'=>'C','Ｄ'=>'D','Ｅ'=>'E','Ｆ'=>'F',
        'Ｇ'=>'G','Ｈ'=>'H','Ｉ'=>'I','Ｊ'=>'J','Ｋ'=>'K','Ｌ'=>'L','Ｍ'=>'M','Ｎ'=>'N', 'Ｏ'=>'O','Ｐ'=>'P','Ｑ'=>'Q',
        'Ｒ'=>'R','Ｓ'=>'S','Ｔ'=>'T','Ｕ'=>'U','Ｖ'=>'V', 'Ｗ'=>'W','Ｘ'=>'X','Ｙ'=>'Y','Ｚ'=>'Z','ａ'=>'a','ｂ'=>'b',
        'ｃ'=>'c','ｄ'=>'d', 'ｅ'=>'e','ｆ'=>'f','ｇ'=>'g','ｈ'=>'h','ｉ'=>'i','ｊ'=>'j','ｋ'=>'k','ｌ'=>'l', 'ｍ'=>'m',
        'ｎ'=>'n','ｏ'=>'o','ｐ'=>'p','ｑ'=>'q','ｒ'=>'r','ｓ'=>'s','ｔ'=>'t', 'ｕ'=>'u','ｖ'=>'v','ｗ'=>'w','ｘ'=>'x',
        'ｙ'=>'y','ｚ'=>'z',);
        return strtr( $text, $trans);
    }

    public function remove_accents_alternative( $text) {
        $from = explode(" ","" ." À Á Â Ã Ä Å Ç È É Ê Ë Ì Í Î Ï Ñ Ò Ó Ô Õ Ö Ø Ù Ú Û Ü Ý à á â" .
            " ã ä å ç è é ê ë ì í î ï ñ ò ó ô õ ö ø ù ú û ü ý ÿ Ā ā Ă ă Ą" ." ą Ć ć Ĉ ĉ Ċ ċ Č č Ď ď Đ đ Ē ē Ĕ ĕ Ė ė Ę ę Ě ě Ĝ ĝ Ğ ğ Ġ ġ Ģ" .
            " ģ Ĥ ĥ Ħ ħ Ĩ ĩ Ī ī Ĭ ĭ Į į İ ı Ĵ ĵ Ķ ķ Ĺ ĺ Ļ ļ Ľ ľ Ŀ ŀ Ł ł Ń" ." ń Ņ ņ Ň ň ŉ Ō ō Ŏ ŏ Ő ő Ŕ ŕ Ŗ ŗ Ř ř Ś ś Ŝ ŝ Ş ş Š š Ţ ţ Ť ť" .
            " Ŧ ŧ Ũ ũ Ū ū Ŭ ŭ Ů ů Ű ű Ų ų Ŵ ŵ Ŷ ŷ Ÿ Ź ź Ż ż Ž ž ƀ Ɓ Ƃ ƃ Ƈ" ." ƈ Ɗ Ƌ ƌ Ƒ ƒ Ɠ Ɨ Ƙ ƙ ƚ Ɲ ƞ Ɵ Ơ ơ Ƥ ƥ ƫ Ƭ ƭ Ʈ Ư ư Ʋ Ƴ ƴ Ƶ ƶ ǅ" .
            " ǈ ǋ Ǎ ǎ Ǐ ǐ Ǒ ǒ Ǔ ǔ Ǖ ǖ Ǘ ǘ Ǚ ǚ Ǜ ǜ Ǟ ǟ Ǡ ǡ Ǥ ǥ Ǧ ǧ Ǩ ǩ Ǫ ǫ" ." Ǭ ǭ ǰ ǲ Ǵ ǵ Ǹ ǹ Ǻ ǻ Ǿ ǿ Ȁ ȁ Ȃ ȃ Ȅ ȅ Ȇ ȇ Ȉ ȉ Ȋ ȋ Ȍ ȍ Ȏ ȏ Ȑ ȑ" .
            " Ȓ ȓ Ȕ ȕ Ȗ ȗ Ș ș Ț ț Ȟ ȟ Ƞ ȡ Ȥ ȥ Ȧ ȧ Ȩ ȩ Ȫ ȫ Ȭ ȭ Ȯ ȯ Ȱ ȱ Ȳ ȳ" ." ȴ ȵ ȶ ȷ Ⱥ Ȼ ȼ Ƚ Ⱦ ȿ ɀ Ƀ Ʉ Ɇ ɇ Ɉ ɉ ɋ Ɍ ɍ Ɏ ɏ ɓ ɕ ɖ ɗ ɟ ɠ ɦ ɨ" .
            " ɫ ɬ ɭ ɱ ɲ ɳ ɵ ɼ ɽ ɾ ʂ ʄ ʈ ʉ ʋ ʐ ʑ ʝ ʠ ͣ ͤ ͥ ͦ ͧ ͨ ͩ ͪ ͫ ͬ ͭ" ." ͮ ͯ ᵢ ᵣ ᵤ ᵥ ᵬ ᵭ ᵮ ᵯ ᵰ ᵱ ᵲ ᵳ ᵴ ᵵ ᵶ ᵻ ᵽ ᵾ ᶀ ᶁ ᶂ ᶃ ᶄ ᶅ ᶆ ᶇ ᶈ ᶉ" .
            " ᶊ ᶌ ᶍ ᶎ ᶏ ᶑ ᶒ ᶖ ᶙ ᷊ ᷗ ᷚ ᷜ ᷝ ᷠ ᷣ ᷤ ᷦ Ḁ ḁ Ḃ ḃ Ḅ ḅ Ḇ ḇ Ḉ ḉ Ḋ ḋ" ." Ḍ ḍ Ḏ ḏ Ḑ ḑ Ḓ ḓ Ḕ ḕ Ḗ ḗ Ḙ ḙ Ḛ ḛ Ḝ ḝ Ḟ ḟ Ḡ ḡ Ḣ ḣ Ḥ ḥ Ḧ ḧ Ḩ ḩ" .
            " Ḫ ḫ Ḭ ḭ Ḯ ḯ Ḱ ḱ Ḳ ḳ Ḵ ḵ Ḷ ḷ Ḹ ḹ Ḻ ḻ Ḽ ḽ Ḿ ḿ Ṁ ṁ Ṃ ṃ Ṅ ṅ Ṇ ṇ" ." Ṉ ṉ Ṋ ṋ Ṍ ṍ Ṏ ṏ Ṑ ṑ Ṓ ṓ Ṕ ṕ Ṗ ṗ Ṙ ṙ Ṛ ṛ Ṝ ṝ Ṟ ṟ Ṡ ṡ Ṣ ṣ Ṥ ṥ" .
            " Ṧ ṧ Ṩ ṩ Ṫ ṫ Ṭ ṭ Ṯ ṯ Ṱ ṱ Ṳ ṳ Ṵ ṵ Ṷ ṷ Ṹ ṹ Ṻ ṻ Ṽ ṽ Ṿ ṿ Ẁ ẁ Ẃ ẃ" ." Ẅ ẅ Ẇ ẇ Ẉ ẉ Ẋ ẋ Ẍ ẍ Ẏ ẏ Ẑ ẑ Ẓ ẓ Ẕ ẕ ẖ ẗ ẘ ẙ ẚ Ạ ạ Ả ả Ấ ấ Ầ" .
            " ầ Ẩ ẩ Ẫ ẫ Ậ ậ Ắ ắ Ằ ằ Ẳ ẳ Ẵ ẵ Ặ ặ Ẹ ẹ Ẻ ẻ Ẽ ẽ Ế ế Ề ề Ể ể Ễ" ." ễ Ệ ệ Ỉ ỉ Ị ị Ọ ọ Ỏ ỏ Ố ố Ồ ồ Ổ ổ Ỗ ỗ Ộ ộ Ớ ớ Ờ ờ Ở ở Ỡ ỡ Ợ" .
            " ợ Ụ ụ Ủ ủ Ứ ứ Ừ ừ Ử ử Ữ ữ Ự ự Ỳ ỳ Ỵ ỵ Ỷ ỷ Ỹ ỹ Ỿ ỿ ⁱ ⁿ ₐ ₑ ₒ" ." ₓ ⒜ ⒝ ⒞ ⒟ ⒠ ⒡ ⒢ ⒣ ⒤ ⒥ ⒦ ⒧ ⒨ ⒩ ⒪ ⒫ ⒬ ⒭ ⒮ ⒯ ⒰ ⒱ ⒲ ⒳ ⒴ ⒵ Ⓐ Ⓑ Ⓒ" .
            " Ⓓ Ⓔ Ⓕ Ⓖ Ⓗ Ⓘ Ⓙ Ⓚ Ⓛ Ⓜ Ⓝ Ⓞ Ⓟ Ⓠ Ⓡ Ⓢ Ⓣ Ⓤ Ⓥ Ⓦ Ⓧ Ⓨ Ⓩ ⓐ ⓑ ⓒ ⓓ ⓔ ⓕ ⓖ" ." ⓗ ⓘ ⓙ ⓚ ⓛ ⓜ ⓝ ⓞ ⓟ ⓠ ⓡ ⓢ ⓣ ⓤ ⓥ ⓦ ⓧ ⓨ ⓩ Ⱡ ⱡ Ɫ Ᵽ Ɽ ⱥ ⱦ Ⱨ ⱨ Ⱪ ⱪ" .
            " Ⱬ ⱬ Ɱ ⱱ Ⱳ ⱳ ⱴ ⱸ ⱺ ⱼ Ꝁ ꝁ Ꝃ ꝃ Ꝅ ꝅ Ꝉ ꝉ Ꝋ ꝋ Ꝍ ꝍ Ꝑ ꝑ Ꝓ ꝓ Ꝕ ꝕ Ꝗ ꝗ" ." Ꝙ ꝙ Ꝛ ꝛ Ꝟ ꝟ Ａ Ｂ Ｃ Ｄ Ｅ Ｆ Ｇ Ｈ Ｉ Ｊ Ｋ Ｌ Ｍ Ｎ Ｏ Ｐ Ｑ Ｒ Ｓ Ｔ Ｕ Ｖ Ｗ Ｘ" .
            " Ｙ Ｚ ａ ｂ ｃ ｄ ｅ ｆ ｇ ｈ ｉ ｊ ｋ ｌ ｍ ｎ ｏ ｐ ｑ ｒ ｓ ｔ ｕ ｖ ｗ ｘ ｙ ｚ");
        $to = explode(" ","" ." A A A A A A C E E E E I I I I N O O O O O O U U U U Y a a a" .
            " a a a c e e e e i i i i n o o o o o o u u u u y y A a A a A" ." a C c C c C c C c D d D d E e E e E e E e E e G g G g G g G" .
            " g H h H h I i I i I i I i I i J j K k L l L l L l L l L l N" ." n N n N n n O o O o O o R r R r R r S s S s S s S s T t T t" .
            " T t U u U u U u U u U u U u W w Y y Y Z z Z z Z z b B B b C" ." c D D d F f G I K k l N n O O o P p t T t T U u V Y y Z z D" .
            " L N A a I i O o U u U u U u U u U u A a A a G g G g K k O o" ." O o j D G g N n A a O o A a A a E e E e I i I i O o O o R r" .
            " R r U u U u S s T t H h N d Z z A a E e O o O o O o O o Y y" ." l n t j A C c L T s z B U E e J j q R r Y y b c d d j g h i" .
            " l l l m n n o r r r s j t u v z z j q a e i o u c d h m r t" ." v x i r u v b d f m n p r r s t z i p u b d f g k l m n p r" .
            " s v x z a d e i u r c g k l n r s z A a B b B b B b C c D d" ." D d D d D d D d E e E e E e E e E e F f G g H h H h H h H h" .
            " H h I i I i K k K k K k L l L l L l L l M m M m M m N n N n" ." N n N n O o O o O o O o P p P p R r R r R r R r S s S s S s" .
            " S s S s T t T t T t T t U u U u U u U u U u V v V v W w W w" ." W w W w W w X x X x Y y Z z Z z Z z h t w y a A a A a A a A" .
            " a A a A a A a A a A a A a A a A a E e E e E e E e E e E e E" ." e E e I i I i O o O o O o O o O o O o O o O o O o O o O o O" .
            " o U u U u U u U u U u U u U u Y y Y y Y y Y y Y y i n a e o" ." x a b c d e f g h i j k l m n o p q r s t u v w x y z A B C" .
            " D E F G H I J K L M N O P Q R S T U V W X Y Z a b c d e f g" ." h i j k l m n o p q r s t u v w x y z L l L P R a t H h K k" .
            " Z z M v W w v e o j K k K k K k L l O o O o P p P p P p Q q" ." Q q R r V v A B C D E F G H I J K L M N O P Q R S T U V W X" .
            " Y Z a b c d e f g h i j k l m n o p q r s t u v w x y z");
            return str_replace( $from, $to, $text);
    }

    function remove_accents_wp( $string ) {
        if ( ! preg_match( '/[\x80-\xff]/', $string ) ) {
            return $string;
        }
        if ( $this->seems_utf8( $string ) ) {
            $chars = array(
                // Decompositions for Latin-1 Supplement
                'ª' => 'a',
                'º' => 'o',
                'À' => 'A',
                'Á' => 'A',
                'Â' => 'A',
                'Ã' => 'A',
                'Ä' => 'A',
                'Å' => 'A',
                'Æ' => 'AE',
                'Ç' => 'C',
                'È' => 'E',
                'É' => 'E',
                'Ê' => 'E',
                'Ë' => 'E',
                'Ì' => 'I',
                'Í' => 'I',
                'Î' => 'I',
                'Ï' => 'I',
                'Ð' => 'D',
                'Ñ' => 'N',
                'Ò' => 'O',
                'Ó' => 'O',
                'Ô' => 'O',
                'Õ' => 'O',
                'Ö' => 'O',
                'Ù' => 'U',
                'Ú' => 'U',
                'Û' => 'U',
                'Ü' => 'U',
                'Ý' => 'Y',
                'Þ' => 'TH',
                'ß' => 's',
                'à' => 'a',
                'á' => 'a',
                'â' => 'a',
                'ã' => 'a',
                'ä' => 'a',
                'å' => 'a',
                'æ' => 'ae',
                'ç' => 'c',
                'è' => 'e',
                'é' => 'e',
                'ê' => 'e',
                'ë' => 'e',
                'ì' => 'i',
                'í' => 'i',
                'î' => 'i',
                'ï' => 'i',
                'ð' => 'd',
                'ñ' => 'n',
                'ò' => 'o',
                'ó' => 'o',
                'ô' => 'o',
                'õ' => 'o',
                'ö' => 'o',
                'ø' => 'o',
                'ù' => 'u',
                'ú' => 'u',
                'û' => 'u',
                'ü' => 'u',
                'ý' => 'y',
                'þ' => 'th',
                'ÿ' => 'y',
                'Ø' => 'O',
                // Decompositions for Latin Extended-A
                'Ā' => 'A',
                'ā' => 'a',
                'Ă' => 'A',
                'ă' => 'a',
                'Ą' => 'A',
                'ą' => 'a',
                'Ć' => 'C',
                'ć' => 'c',
                'Ĉ' => 'C',
                'ĉ' => 'c',
                'Ċ' => 'C',
                'ċ' => 'c',
                'Č' => 'C',
                'č' => 'c',
                'Ď' => 'D',
                'ď' => 'd',
                'Đ' => 'D',
                'đ' => 'd',
                'Ē' => 'E',
                'ē' => 'e',
                'Ĕ' => 'E',
                'ĕ' => 'e',
                'Ė' => 'E',
                'ė' => 'e',
                'Ę' => 'E',
                'ę' => 'e',
                'Ě' => 'E',
                'ě' => 'e',
                'Ĝ' => 'G',
                'ĝ' => 'g',
                'Ğ' => 'G',
                'ğ' => 'g',
                'Ġ' => 'G',
                'ġ' => 'g',
                'Ģ' => 'G',
                'ģ' => 'g',
                'Ĥ' => 'H',
                'ĥ' => 'h',
                'Ħ' => 'H',
                'ħ' => 'h',
                'Ĩ' => 'I',
                'ĩ' => 'i',
                'Ī' => 'I',
                'ī' => 'i',
                'Ĭ' => 'I',
                'ĭ' => 'i',
                'Į' => 'I',
                'į' => 'i',
                'İ' => 'I',
                'ı' => 'i',
                'Ĳ' => 'IJ',
                'ĳ' => 'ij',
                'Ĵ' => 'J',
                'ĵ' => 'j',
                'Ķ' => 'K',
                'ķ' => 'k',
                'ĸ' => 'k',
                'Ĺ' => 'L',
                'ĺ' => 'l',
                'Ļ' => 'L',
                'ļ' => 'l',
                'Ľ' => 'L',
                'ľ' => 'l',
                'Ŀ' => 'L',
                'ŀ' => 'l',
                'Ł' => 'L',
                'ł' => 'l',
                'Ń' => 'N',
                'ń' => 'n',
                'Ņ' => 'N',
                'ņ' => 'n',
                'Ň' => 'N',
                'ň' => 'n',
                'ŉ' => 'n',
                'Ŋ' => 'N',
                'ŋ' => 'n',
                'Ō' => 'O',
                'ō' => 'o',
                'Ŏ' => 'O',
                'ŏ' => 'o',
                'Ő' => 'O',
                'ő' => 'o',
                'Œ' => 'OE',
                'œ' => 'oe',
                'Ŕ' => 'R',
                'ŕ' => 'r',
                'Ŗ' => 'R',
                'ŗ' => 'r',
                'Ř' => 'R',
                'ř' => 'r',
                'Ś' => 'S',
                'ś' => 's',
                'Ŝ' => 'S',
                'ŝ' => 's',
                'Ş' => 'S',
                'ş' => 's',
                'Š' => 'S',
                'š' => 's',
                'Ţ' => 'T',
                'ţ' => 't',
                'Ť' => 'T',
                'ť' => 't',
                'Ŧ' => 'T',
                'ŧ' => 't',
                'Ũ' => 'U',
                'ũ' => 'u',
                'Ū' => 'U',
                'ū' => 'u',
                'Ŭ' => 'U',
                'ŭ' => 'u',
                'Ů' => 'U',
                'ů' => 'u',
                'Ű' => 'U',
                'ű' => 'u',
                'Ų' => 'U',
                'ų' => 'u',
                'Ŵ' => 'W',
                'ŵ' => 'w',
                'Ŷ' => 'Y',
                'ŷ' => 'y',
                'Ÿ' => 'Y',
                'Ź' => 'Z',
                'ź' => 'z',
                'Ż' => 'Z',
                'ż' => 'z',
                'Ž' => 'Z',
                'ž' => 'z',
                'ſ' => 's',
                // Decompositions for Latin Extended-B
                'Ș' => 'S',
                'ș' => 's',
                'Ț' => 'T',
                'ț' => 't',
                // Euro Sign
                '€' => 'E',
                // GBP (Pound) Sign
                '£' => '',
                // Vowels with diacritic (Vietnamese)
                // unmarked
                'Ơ' => 'O',
                'ơ' => 'o',
                'Ư' => 'U',
                'ư' => 'u',
                // grave accent
                'Ầ' => 'A',
                'ầ' => 'a',
                'Ằ' => 'A',
                'ằ' => 'a',
                'Ề' => 'E',
                'ề' => 'e',
                'Ồ' => 'O',
                'ồ' => 'o',
                'Ờ' => 'O',
                'ờ' => 'o',
                'Ừ' => 'U',
                'ừ' => 'u',
                'Ỳ' => 'Y',
                'ỳ' => 'y',
                // hook
                'Ả' => 'A',
                'ả' => 'a',
                'Ẩ' => 'A',
                'ẩ' => 'a',
                'Ẳ' => 'A',
                'ẳ' => 'a',
                'Ẻ' => 'E',
                'ẻ' => 'e',
                'Ể' => 'E',
                'ể' => 'e',
                'Ỉ' => 'I',
                'ỉ' => 'i',
                'Ỏ' => 'O',
                'ỏ' => 'o',
                'Ổ' => 'O',
                'ổ' => 'o',
                'Ở' => 'O',
                'ở' => 'o',
                'Ủ' => 'U',
                'ủ' => 'u',
                'Ử' => 'U',
                'ử' => 'u',
                'Ỷ' => 'Y',
                'ỷ' => 'y',
                // tilde
                'Ẫ' => 'A',
                'ẫ' => 'a',
                'Ẵ' => 'A',
                'ẵ' => 'a',
                'Ẽ' => 'E',
                'ẽ' => 'e',
                'Ễ' => 'E',
                'ễ' => 'e',
                'Ỗ' => 'O',
                'ỗ' => 'o',
                'Ỡ' => 'O',
                'ỡ' => 'o',
                'Ữ' => 'U',
                'ữ' => 'u',
                'Ỹ' => 'Y',
                'ỹ' => 'y',
                // acute accent
                'Ấ' => 'A',
                'ấ' => 'a',
                'Ắ' => 'A',
                'ắ' => 'a',
                'Ế' => 'E',
                'ế' => 'e',
                'Ố' => 'O',
                'ố' => 'o',
                'Ớ' => 'O',
                'ớ' => 'o',
                'Ứ' => 'U',
                'ứ' => 'u',
                // dot below
                'Ạ' => 'A',
                'ạ' => 'a',
                'Ậ' => 'A',
                'ậ' => 'a',
                'Ặ' => 'A',
                'ặ' => 'a',
                'Ẹ' => 'E',
                'ẹ' => 'e',
                'Ệ' => 'E',
                'ệ' => 'e',
                'Ị' => 'I',
                'ị' => 'i',
                'Ọ' => 'O',
                'ọ' => 'o',
                'Ộ' => 'O',
                'ộ' => 'o',
                'Ợ' => 'O',
                'ợ' => 'o',
                'Ụ' => 'U',
                'ụ' => 'u',
                'Ự' => 'U',
                'ự' => 'u',
                'Ỵ' => 'Y',
                'ỵ' => 'y',
                // Vowels with diacritic (Chinese, Hanyu Pinyin)
                'ɑ' => 'a',
                // macron
                'Ǖ' => 'U',
                'ǖ' => 'u',
                // acute accent
                'Ǘ' => 'U',
                'ǘ' => 'u',
                // caron
                'Ǎ' => 'A',
                'ǎ' => 'a',
                'Ǐ' => 'I',
                'ǐ' => 'i',
                'Ǒ' => 'O',
                'ǒ' => 'o',
                'Ǔ' => 'U',
                'ǔ' => 'u',
                'Ǚ' => 'U',
                'ǚ' => 'u',
                // grave accent
                'Ǜ' => 'U',
                'ǜ' => 'u',
            );
            // Used for locale-specific rules
            $locale = "cs_CS";
            if ( 'de_DE' == $locale || 'de_DE_formal' == $locale || 'de_CH' == $locale || 'de_CH_informal' == $locale ) {
                $chars['Ä'] = 'Ae';
                $chars['ä'] = 'ae';
                $chars['Ö'] = 'Oe';
                $chars['ö'] = 'oe';
                $chars['Ü'] = 'Ue';
                $chars['ü'] = 'ue';
                $chars['ß'] = 'ss';
            } elseif ( 'da_DK' === $locale ) {
                $chars['Æ'] = 'Ae';
                $chars['æ'] = 'ae';
                $chars['Ø'] = 'Oe';
                $chars['ø'] = 'oe';
                $chars['Å'] = 'Aa';
                $chars['å'] = 'aa';
            } elseif ( 'ca' === $locale ) {
                $chars['l·l'] = 'll';
            } elseif ( 'sr_RS' === $locale || 'bs_BA' === $locale ) {
                $chars['Đ'] = 'DJ';
                $chars['đ'] = 'dj';
            }
            $string = strtr( $string, $chars );
        } else {
            $chars = array();
            // Assume ISO-8859-1 if not UTF-8
            $chars['in'] = "\x80\x83\x8a\x8e\x9a\x9e"
                . "\x9f\xa2\xa5\xb5\xc0\xc1\xc2"
                . "\xc3\xc4\xc5\xc7\xc8\xc9\xca"
                . "\xcb\xcc\xcd\xce\xcf\xd1\xd2"
                . "\xd3\xd4\xd5\xd6\xd8\xd9\xda"
                . "\xdb\xdc\xdd\xe0\xe1\xe2\xe3"
                . "\xe4\xe5\xe7\xe8\xe9\xea\xeb"
                . "\xec\xed\xee\xef\xf1\xf2\xf3"
                . "\xf4\xf5\xf6\xf8\xf9\xfa\xfb"
                . "\xfc\xfd\xff";
            $chars['out'] = 'EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy';
            $string              = strtr( $string, $chars['in'], $chars['out'] );
            $double_chars        = array();
            $double_chars['in']  = array( "\x8c", "\x9c", "\xc6", "\xd0", "\xde", "\xdf", "\xe6", "\xf0", "\xfe" );
            $double_chars['out'] = array( 'OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th' );
            $string              = str_replace( $double_chars['in'], $double_chars['out'], $string );
        }
        return $string;
    }

    private function seems_utf8( $str ) {
        $this->mbstring_binary_safe_encoding();
        $length = strlen( $str );
        $this->mbstring_binary_safe_encoding(true);
        for ( $i = 0; $i < $length; $i++ ) {
            $c = ord( $str[ $i ] );
            if ( $c < 0x80 ) {
                $n = 0; // 0bbbbbbb
            } elseif ( ( $c & 0xE0 ) == 0xC0 ) {
                $n = 1; // 110bbbbb
            } elseif ( ( $c & 0xF0 ) == 0xE0 ) {
                $n = 2; // 1110bbbb
            } elseif ( ( $c & 0xF8 ) == 0xF0 ) {
                $n = 3; // 11110bbb
            } elseif ( ( $c & 0xFC ) == 0xF8 ) {
                $n = 4; // 111110bb
            } elseif ( ( $c & 0xFE ) == 0xFC ) {
                $n = 5; // 1111110b
            } else {
                return false; // Does not match any model
            }
            for ( $j = 0; $j < $n; $j++ ) { // n bytes matching 10bbbbbb follow ?
                if ( ( ++$i == $length ) || ( ( ord( $str[ $i ] ) & 0xC0 ) != 0x80 ) ) {
                    return false;
                }
            }
        }
        return true;
    }

    private function mbstring_binary_safe_encoding( $reset = false ) {
        static $encodings = array();
        static $overloaded = null;

        if ( is_null( $overloaded ) )
            $overloaded = function_exists( 'mb_internal_encoding' ) && ( ini_get( 'mbstring.func_overload' ) & 2 );

        if ( false === $overloaded )
            return;

        if ( ! $reset ) {
            $encoding = mb_internal_encoding();
            array_push( $encodings, $encoding );
            mb_internal_encoding( 'ISO-8859-1' );
        }

        if ( $reset && $encodings ) {
            $encoding = array_pop( $encodings );
            mb_internal_encoding( $encoding );
        }
    }

}
