<?php
/**
 * Parsi date main conversation class
 *
 * @author              Mobin Ghasempoor
 * @package             WP-Parsidate
 * @subpackage          DateConversation
 */
/*Special thanks to :
Reza Gholampanahi for convert function*/

class bn_parsidate_custom {
    protected static $instance;
    public $persian_month_names = array(
        '',
        'فروردین',
        'اردیبهشت',
        'خرداد',
        'تیر',
        'مرداد',
        'شهریور',
        'مهر',
        'آبان',
        'آذر',
        'دی',
        'بهمن',
        'اسفند'
    );
    public $persian_short_month_names = array(
        '',
        'فروردین',
        'اردیبهشت',
        'خرداد',
        'تیر',
        'مرداد',
        'شهریور',
        'مهر',
        'آبان',
        'آذر',
        'دی',
        'بهمن',
        'اسفند'
    );
    public $sesson = array( 'بهار', 'تابستان', 'پاییز', 'زمستان' );

    public $persian_day_names = array( 'یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه', 'شنبه' );
    public $persian_day_small = array( 'ی', 'د', 'س', 'چ', 'پ', 'ج', 'ش' );

    public $j_days_in_month   = array( 31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29 );
    private $j_days_sum_month = array( 0, 0, 31, 62, 93, 124, 155, 186, 216, 246, 276, 306, 336 );

    private $g_days_sum_month = array( 0, 0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334 );


    /**
     * Constructor
     */
    function __construct() {
    }

    /**
     * bn_parsidate::IsPerLeapYear()
     * check year is leap
     *
     * @param mixed $year
     *
     * @return boolean
     */
    public function IsPerLeapYear($year) {
        $mod = $year % 33;

        if ($mod == 1 or $mod == 5 or $mod == 9 or $mod == 13 or $mod == 17 or $mod == 22 or $mod == 26 or $mod == 30) {
            return true;
        }
        return false;
    }

    /**
     * bn_parsidate::IsLeapYear()
     * check year is leap
     *
     * @param mixed $year
     *
     * @return boolean
     */
    private function IsLeapYear( $year ) {
        if ( ( ( $year % 4 ) == 0 && ( $year % 100 ) != 0 ) || ( ( $year % 400 ) == 0 ) && ( $year % 100 ) == 0 ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * bn_parsidate::persian_date()
     * convert gregorian datetime to persian datetime
     *
     * @param mixed $format
     * @param string $date
     * @param string $lang
     *
     * @return datetime
     */
    public function persian_date( $format, $date = 'now', $lang = 'per' ) {

        $j_days_in_month = array( 31, 62, 93, 124, 155, 186, 216, 246, 276, 306, 336, 365 );
        $timestamp       = is_numeric( $date ) && (int) $date == $date ? $date : strtotime( $date );
        $date            = getdate( $timestamp );
        list( $date['year'], $date['mon'], $date['mday'] ) = self::gregorian_to_persian( $date['year'], $date['mon'], $date['mday'] );
        $date['mon']  = (int) $date['mon'];
        $date['mday'] = (int) $date['mday'];
        $out          = '';
        $len          = strlen( $format );
        for ( $i = 0; $i <$len; $i ++ ) {
            Switch ( $format[ $i ] ) {
                //day
                case'd':
                    $out .= ( $date['mday'] < 10 ) ? '0' . $date['mday'] : $date['mday'];
                    break;
                case'D':
                    $out .= $this->persian_day_small[ $date['wday'] ];
                    break;
                case'l':
                    $out .= $this->persian_day_names[ $date['wday'] ];
                    break;
                case'j':
                    $out .= $date['mday'];
                    break;
                case'N':
                    $out .= $this->week_day( $date['wday'] ) + 1;
                    break;
                case'w':
                    $out .= $this->week_day( $date['wday'] );
                    break;
                case'z':
                    if($date['mon']==12 && self::IsPerLeapYear($date['year']))
                        $out .= 30 + $date['mday'];
                    else
                        $out .= $this->j_days_in_month[ $date['mon'] ] + $date['mday'];
                    break;
                //week
                case'W':
                    $yday = $this->j_days_sum_month[ $date['mon'] - 1 ] + $date['mday'];
                    $out  .= intval( $yday / 7 );
                    break;
                //month
                case'f':
                    $mon = $date['mon'];
                    switch ( $mon ) {
                        case( $mon < 4 ):
                            $out .= $this->sesson[0];
                            break;
                        case( $mon < 7 ):
                            $out .= $this->sesson[1];
                            break;
                        case( $mon < 10 ):
                            $out .= $this->sesson[2];
                            break;
                        case( $mon > 9 ):
                            $out .= $this->sesson[3];
                            break;
                    }
                    break;
                case'F':
                    $out .= $this->persian_month_names[ (int) $date['mon'] ];
                    break;
                case'm':
                    $out .= ( $date['mon'] < 10 ) ? '0' . $date['mon'] : $date['mon'];
                    break;
                case'M':
                    $out .= $this->persian_short_month_names[ (int) $date['mon'] ];
                    break;
                case'n':
                    $out .= $date['mon'];
                    break;
                case'S':
                    $out .= 'ام';
                    break;
                case't':
                    if($date['mon']==12 && self::IsPerLeapYear($date['year']))
                        $out .= 30;
                    else
                        $out .= $this->j_days_in_month[ (int) $date['mon'] - 1 ];
                    break;
                //year
                case'L':
                    $out .= ( ( $date['year'] % 4 ) == 0 ) ? 1 : 0;
                    break;
                case'o':
                case'Y':
                    $out .= $date['year'];
                    break;
                case'y':
                    $out .= substr( $date['year'], 2, 2 );
                    break;
                //time
                case'a':
                    $out .= ( $date['hours'] < 12 ) ? 'ق.ظ' : 'ب.ظ';
                    break;
                case'A':
                    $out .= ( $date['hours'] < 12 ) ? 'قبل از ظهر' : 'بعد از ظهر';
                    break;
                case'B':
                    $out .= (int) ( 1 + ( $date['mon'] / 3 ) );
                    break;
                case'g':
                    $out .= ( $date['hours'] > 12 ) ? $date['hours'] - 12 : $date['hours'];
                    break;
                case'G':
                    $out .= $date['hours'];
                    break;
                case'h':
                    $hour = ( $date['hours'] > 12 ) ? $date['hours'] - 12 : $date['hours'];
                    $out  .= ( $hour < 10 ) ? '0' . $hour : $hour;
                    break;
                case'H':
                    $out .= ( $date['hours'] < 10 ) ? '0' . $date['hours'] : $date['hours'];
                    break;
                case'i':
                    $out .= ( $date['minutes'] < 10 ) ? '0' . $date['minutes'] : $date['minutes'];
                    break;
                case's':
                    $out .= ( $date['seconds'] < 10 ) ? '0' . $date['seconds'] : $date['seconds'];
                    break;
                //full date time
                case'c':
                    $out = $date['year'] . '/' . $date['mon'] . '/' . $date['mday'] . ' ' . $date['hours'] . ':' . ( ( $date['minutes'] < 10 ) ? '0' . $date['minutes'] : $date['minutes'] ) . ':' . ( ( $date['seconds'] < 10 ) ? '0' . $date['seconds'] : $date['seconds'] );//2004-02-12T15:19:21+00:00
                    break;
                case'r':
                    $out = $this->persian_day_names[ $date['wday'] ] . ',' . $date['mday'] . ' ' . $this->persian_month_names[ (int) $date['mon'] ] . ' ' . $date['year'] . ' ' . $date['hours'] . ':' . ( ( $date['minutes'] < 10 ) ? '0' . $date['minutes'] : $date['minutes'] ) . ':' . ( ( $date['seconds'] < 10 ) ? '0' . $date['seconds'] : $date['seconds'] );//Thu, 21 Dec 2000 16:01:07
                    break;
                case'U':
                    $out = $timestamp;
                    break;
                //others
                case'e':
                case'I':
                case'i':
                case'O':
                case'P':
                case'T':
                case'Z':
                case'u':
                    break;
                default:
                    $out .= $format[ $i ];
            }
        }
        if ( $lang == 'per' ) {
            return self::trim_number( $out );
        } else {
            return $out;
        }
    }

    /**
     * bn_parsidate::gregorian_to_persian()
     * convert gregorian date to persian date
     *
     * @param mixed $gy
     * @param mixed $gm
     * @param mixed $gd
     *
     * @return array
     */
    function gregorian_to_persian( $gy, $gm, $gd ) {
        $dayofyear = $this->g_days_sum_month[ (int) $gm ] + $gd;
        if ( self::IsLeapYear( $gy ) and $gm > 2 ) {
            $dayofyear ++;
        }
        $d_33 = (int) ( ( ( $gy - 16 ) % 132 ) * 0.0305 );
        $leap = $gy % 4;
        $a    = ( ( $d_33 == 1 or $d_33 == 2 ) and ( $d_33 == $leap or $leap == 1 ) ) ? 78 : ( ( $d_33 == 3 and $leap == 0 ) ? 80 : 79 );
        $b    = ( $d_33 == 3 or $d_33 < ( $leap - 1 ) or $leap == 0 ) ? 286 : 287;
        if ( (int) ( ( $gy - 10 ) / 63 ) == 30 ) {
            $b --;
            $a ++;
        }
        if ( $dayofyear > $a ) {
            $jy = $gy - 621;
            $jd = $dayofyear - $a;
        } else {
            $jy = $gy - 622;
            $jd = $dayofyear + $b;
        }
        for ( $i = 0; $i < 11 and $jd > $this->j_days_in_month[ $i ]; $i ++ ) {
            $jd -= $this->j_days_in_month[ $i ];
        }
        $jm = ++ $i;

        return array( $jy, strlen( $jm ) == 1 ? '0' . $jm : $jm, strlen( $jd ) == 1 ? '0' . $jd : $jd );
    }

    /**
     * Get day of the week shamsi/jalali
     * @author       Parsa Kafi
     *
     * @param        int $wday
     *
     * @return       int
     */
    private function week_day( $wday ) {

        return $wday == 6?0:++ $wday;
    }

    /**
     * bn_parsidate::trim_number()
     * convert english number to persian number
     *
     * @param mixed $num
     * @param string $sp
     *
     * @return string
     */
    public function trim_number( $num, $sp = '٫' ) {
        $eng    = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.' );
        $per    = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $sp );
        $number = filter_var( $num, FILTER_SANITIZE_NUMBER_INT );

        return empty( $number ) ? str_replace( $per, $eng, $num ) : str_replace( $eng, $per, $num );
    }

    /**
     * bn_parsidate::getInstance()
     * create instance of bn_parsidate class
     *
     * @return instance
     */
    public static function getInstance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * bn_parsidate::gregurian_date()
     * convert persian datetime to gregorian datetime
     *
     * @param mixed $format
     * @param mixed $persiandate
     *
     * @return mixed
     */
    public function gregurian_date( $format, $persiandate ) {
        preg_match_all( '!\d+!', $persiandate, $matches );
        $matches = $matches[0];
        list( $year, $mon, $day ) = self::persian_to_gregorian( $matches[0], $matches[1], $matches[2] );

        return date( $format, mktime( ( isset( $matches[3] ) ? $matches[3] : 0 ), ( isset( $matches[4] ) ? $matches[4] : 0 ), ( isset( $matches[5] ) ? $matches[5] : 0 ), $mon, $day, $year ) );
    }

    /**
     * bn_parsidate::persian_to_gregorian()
     * convert persian date to gregorian date
     *
     * @param mixed $jy
     * @param mixed $jm
     * @param mixed $jd
     *
     * @return array
     */
    public function persian_to_gregorian( $jy, $jm, $jd ) {
        $doyj = ( $jm - 2 > - 1 ? $this->j_days_sum_month[ (int) $jm ] + $jd : $jd );
        $d4   = ( $jy + 1 ) % 4;
        $d33  = (int) ( ( ( $jy - 55 ) % 132 ) * .0305 );
        $a    = ( $d33 != 3 and $d4 <= $d33 ) ? 287 : 286;
        $b    = ( ( $d33 == 1 or $d33 == 2 ) and ( $d33 == $d4 or $d4 == 1 ) ) ? 78 : ( ( $d33 == 3 and $d4 == 0 ) ? 80 : 79 );
        if ( (int) ( ( $jy - 19 ) / 63 ) == 20 ) {
            $a --;
            $b ++;
        }
        if ( $doyj <= $a ) {
            $gy = $jy + 621;
            $gd = $doyj + $b;
        } else {
            $gy = $jy + 622;
            $gd = $doyj - $a;
        }
        foreach ( array( 0, 31, ( $gy % 4 == 0 ) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ) as $gm => $days ) {
            if ( $gd <= $days ) {
                break;
            }
            $gd -= $days;
        }
        return array( $gy, $gm, $gd );
    }

    /**
     * Convert given Gregorian date into Hijri date
     *
     * @param integer $Y Year Gregorian year
     * @param integer $M Month Gregorian month
     * @param integer $D Day Gregorian day
     *
     * @return array Hijri date [int Year, int Month, int Day](Islamic calendar)
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function hjConvert($Y, $M, $D)
    {
        if (function_exists('GregorianToJD')) {
            $jd = GregorianToJD($M, $D, $Y);
        } else {
            $jd = $this->gregToJd($M, $D, $Y);
        }

        list($year, $month, $day) = $this->jdToIslamic($jd);

        return array($year, $month, $day);
    }

    /**
     * Convert given Julian day into Hijri date
     *
     * @param integer $jd Julian day
     *
     * @return array Hijri date [int Year, int Month, int Day](Islamic calendar)
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function jdToIslamic($jd)
    {
        $l = (int)$jd - 1948440 + 10632;
        $n = (int)(($l - 1) / 10631);
        $l = $l - 10631 * $n + 354;
        $j = (int)((10985 - $l) / 5316) * (int)((50 * $l) / 17719)
            + (int)($l / 5670) * (int)((43 * $l) / 15238);
        $l = $l - (int)((30 - $j) / 15) * (int)((17719 * $j) / 50)
            - (int)($j / 16) * (int)((15238 * $j) / 43) + 29;
        $m = (int)((24 * $l) / 709);
        $d = $l - (int)((709 * $m) / 24);
        $y = (int)(30 * $n + $j - 30);

        return array($y, $m, $d);
    }

    /**
     * Convert given Hijri date into Julian day
     *
     * @param integer $year  Year Hijri year
     * @param integer $month Month Hijri month
     * @param integer $day   Day Hijri day
     *
     * @return integer Julian day
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function islamicToJd($year, $month, $day)
    {
        $jd = (int)((11 * $year + 3) / 30) + (int)(354 * $year) + (int)(30 * $month)
            - (int)(($month - 1) / 2) + $day + 1948440 - 385;
        return $jd;
    }

    /**
     * Converts a Gregorian date to Julian Day Count
     *
     * @param integer $m The month as a number from 1 (for January)
     *                    to 12 (for December)
     * @param integer $d The day as a number from 1 to 31
     * @param integer $y The year as a number between -4714 and 9999
     *
     * @return integer The julian day for the given gregorian date as an integer
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function gregToJd ($m, $d, $y)
    {
        if ($m < 3) {
            $y--;
            $m += 12;
        }

        if (($y < 1582) || ($y == 1582 && $m < 10)
            || ($y == 1582 && $m == 10 && $d <= 15)
        ) {
            // This is ignored in the GregorianToJD PHP function!
            $b = 0;
        } else {
            $a = (int)($y / 100);
            $b = 2 - $a + (int)($a / 4);
        }

        $jd = (int)(365.25 * ($y + 4716)) + (int)(30.6001 * ($m + 1))
            + $d + $b - 1524.5;

        return round($jd);
    }

    public function getMonthName($format,$index){
        $gregorian_month_names = array(
            '',
            'ژانویه',
            'فوریه',
            'مارس',
            'آوریل',
            'می',
            'ژوئن',
            'جولای',
            'آگوست',
            'سپتامبر',
            'اکتبر',
            'نوامبر',
            'دسامبر'
        );
        $hejri_month_names = array(
            '',
            'محرم',
            'صفر',
            'ربیع الاول',
            'ربیع الثانی',
            'جمادی الاول',
            'جمادی الثانی',
            'رجب',
            'شعبان',
            'رمضان',
            'شوال',
            'ذیقعده',
            'ذیحجه'
        );
        if($format=="m") return $gregorian_month_names[$index];
        if($format=="h") return $hejri_month_names[$index];

    }

    public function getDateoccasions($format,$month,$day){
        $shamsi_occasions = [
            "m_1"=>[
            "d_1"=>["desc"=>"آغاز نوروز (تعطیل)","is_off"=>true],
            "d_2"=>["desc"=>"عید نوروز - تهاجم ماموران پهلوی به مدرسه فیضیه قم (1342هـ.ش) - آغاز عملیات فتح المبین (1361هـ.ش) (تعطیل)","is_off"=>true],
            "d_3"=>["desc"=>"عید نوروز (تعطیل)","is_off"=>true],
            "d_4"=>["desc"=>"عید نوروز (تعطیل)","is_off"=>true],
            "d_6"=>["desc"=>"روز امید ، روزشادباش نویسی","is_off"=>false],
            "d_12"=>["desc"=>"روز جمهوری اسلامی ایران (تعطیل)","is_off"=>true],
            "d_13"=>["desc"=>"روز طبیعت (تعطیل)","is_off"=>true],
            "d_17"=>["desc"=>"سروش روز ، جشن سروشگان","is_off"=>false],
            "d_18"=>["desc"=>"شهادت آیت الله سید محمد باقر صدر و خواهر ایشان بنت الهدی توسط حکومت بعث عراق (1359هـ.ش) - فروردین روز ، جشن فروردینگان","is_off"=>false],
            "d_20"=>["desc"=>"روز ملّی فن آوری هسته ای","is_off"=>false],
            "d_21"=>["desc"=>"روز هنر انقلاب اسلامی ، شهادت امیر سپهبد علی سیاد شیرازی (1378هـ.ش)","is_off"=>false],
            "d_25"=>["desc"=>"روز بزرگداشت عطار نیشابوری","is_off"=>false],
            "d_29"=>["desc"=>"روز ارتش جمهوری اسلامی و نیروی زمینی","is_off"=>false],

            ],
            "m_2"=>[
                "d_1"=>["desc"=>"روز بزرگداشت سعدی","is_off"=>false],
                "d_2"=>["desc"=>"تاسیس سپاه پاسداران انقلاب اسلامی (1358هـ.ش) - سالروز اعلام انقلاب فرهنگی (1359هـ.ش) - جشن گیاه آوری ، روز زمین پاک","is_off"=>false],
                "d_3"=>["desc"=>"روز بزرگداشت شیخ بهایی","is_off"=>false],
                "d_5"=>["desc"=>"شکست حمله ی نظامی آمریکا به ایران در طبس (1359هـ.ش)","is_off"=>false],
                "d_9"=>["desc"=>"روز شوراها","is_off"=>false],
                "d_10"=>["desc"=>"روز ملی خلیج فارس - آغاز عملیات بیت المقدس (1361هـ.ش) - جشن چهل ام نوروز","is_off"=>false],
                "d_12"=>["desc"=>"شهادت استاد مرتضی مطهری (1358هـ.ش) روز معلم","is_off"=>false],
                "d_15"=>["desc"=>"روز بزرگداشت شیخ صدوق - جشن میانه بهار ، جشن بهاربد","is_off"=>false],
                "d_24"=>["desc"=>"لغو امتیاز تنباکو به فتوای آیت الله میرزا حسن شیرازی (1270)","is_off"=>false],
                "d_25"=>["desc"=>"روز بزرگداشت فردوسی","is_off"=>false],
                "d_27"=>["desc"=>"روز ارتباطات و روابط عمومی","is_off"=>false],
                "d_28"=>["desc"=>"روز بزرگداشت حکیم عمر خیام","is_off"=>false]
                ]
            ,
            "m_3"=>[
                "d_1"=>["desc"=>"روز بهره وری و بهینه سازی مصرف - روز بزرگداشت ملاصدرا","is_off"=>false],
                "d_3"=>["desc"=>"فتح خرمشهر در عملیات بیت المقدس ( 1361 هـ.ش) - روز مقاومت ایثار و پیروزی","is_off"=>false],
                "d_4"=>["desc"=>"روز مقاومت و پایداری","is_off"=>false],
                "d_6"=>["desc"=>"خرداد روز ، جشن خردادگان","is_off"=>false],
                "d_13"=>["desc"=>"تیر روز ، جشن تیرگان","is_off"=>false],
                "d_14"=>["desc"=>"رحلت امام خمینی (ره) رهبر کبیر انقلاب و بنیانگذار جمهوری اسلامی ایران (1368 هـ.ش) - انتخاب آیت الله خامنه ای به رهبری (1368 هـ.ش) (تعطیل)","is_off"=>true],
                "d_15"=>["desc"=>"قیام خونین 15 خرداد (1342 هـ.ش) (تعطیل)","is_off"=>true],
                "d_20"=>["desc"=>"شهادت آیت الله سعیدی به دست مأموران ستم شاهی (1349 هـ.ش)","is_off"=>false],
                "d_29"=>["desc"=>"درگذشت دکتر علی شریعتی (1356 هـ.ش)","is_off"=>false],
                "d_30"=>["desc"=>"انفجار در حرم حضرت امام رضا (ع) به دست ایادی امریکا (1373 هـ.ش)","is_off"=>false],
                "d_31"=>["desc"=>"روز بسیج اساتید و شهادت دکتر مصطفی چمران (1360 هـ.ش)","is_off"=>false],

            ],
            "m_4"=>[
                "d_1"=>["desc"=>"روز تبلیغ و اطلاع رسانی دینی ، سالروز صدور فرمان امام خمینی (ره) مبنی بر تاسیس سازمان تبلیغات اسلامی (1360 هـ.ش) - روز اصناف - جشن آب پاشونک و آغاز تابستان","is_off"=>false],
                "d-7"=>["desc"=>"شهادت مظلومانه ی آیت الله دکتر بهشتی و 72 تن از یاران امام خمینی (ره) با انفجار بمب منافقان در دفتر مرکزی حزب جمهوری اسلامی (1360 هـ.ش) - روز قوه ی قضائیه","is_off"=>false],
                "d_8"=>["desc"=>"روز مبارزه با سلاح های شیمیایی و میکروبی","is_off"=>false],
                "d_10"=>["desc"=>"روز صنعت و معدن","is_off"=>false],
                "d_11"=>["desc"=>"شهادت آیت الله صدوقی چهارمین شهید محراب به دست منافقان (1361 هـ.ش)","is_off"=>false],
                "d_12"=>["desc"=>"حمله به هواپیمای مسافربری جمهوری اسلامی ایران توسط ناوگان آمریکای جنایتکار (1368 هـ.ش)","is_off"=>false],
                "d_13"=>["desc"=>"سالروز درگذشت دکتر معین","is_off"=>false],
                "d_14"=>["desc"=>"روز قلم ، تولد حضرت امام خمینی (ره)","is_off"=>false],
                "d_15"=>["desc"=>"جشن خام خواری","is_off"=>false],
                "d_16"=>["desc"=>"روز مالیات","is_off"=>false],
                "d_21"=>["desc"=>"روز عفاف و حجاب (سالروز قیام مردم مشهد علیه کشف حجاب و کشتار مسجد گوهرشاد توسّط مأموران حکومت رضاخان) (1314 هـ.ش)","is_off"=>false],
                "d_25"=>["desc"=>"روز بهزیستی و تامین اجتماعی","is_off"=>false],
                "d_27"=>["desc"=>"اعلام پذیرش قطعنامه 598 شورای امنیت از سوی ایران (1368 هـ.ش)","is_off"=>false],

            ],
            "m_5"=>[
                "d_5"=>["desc"=>"سالروز عملیات افتخار آفرین مرصاد (1367 هـ.ش)","is_off"=>false],
                "d_6"=>["desc"=>"روز ترویج آموزش های فنی و حرفه ای","is_off"=>false],
                "d_7"=>["desc"=>"مرداد روز ، جشن مردادگان","is_off"=>false],
                "d_8"=>["desc"=>"روز بزرگداشت شیخ شهاب الدین سهروردی","is_off"=>false],
                "d_9"=>["desc"=>"روز اهدای خون","is_off"=>false],
                "d_10"=>["desc"=>"جشن چله تابستان","is_off"=>false],
                "d_14"=>["desc"=>"صدور فرمان مشروطیت","is_off"=>false],
                "d_16"=>["desc"=>"تشکیل جهاد دانشگاهی (1359 هـ.ش)","is_off"=>false],
                "d_17"=>["desc"=>"روز خبرنگار","is_off"=>false],
                "d_28"=>["desc"=>"کودتای آمریکا برای باز گرداندن شاه (1332 هـ.ش)","is_off"=>false],
                "d_30"=>["desc"=>"روز بزرگداشت علامه مجلسی","is_off"=>false],

            ],
            "m_6"=>[
                "d_1"=>["desc"=>"روز بزرگداشت بوعلی سینا و روز پزشک","is_off"=>false],
                "d_2"=>["desc"=>"آغاز هفته دولت","is_off"=>false],
                "d_4"=>["desc"=>"روز کارمند - زادروز داراب (کوروش) - شهریور روز ، جشن شهریورگان","is_off"=>false],
                "d_5"=>["desc"=>"روز بزرگداشت محمد بن زکریای رازی و روز داروسازی","is_off"=>false],
                "d_8"=>["desc"=>"روز مبارزه با تروریسم ، انفجار دفتر نخست وزیری به دست منافقان و شهادت مظلومانه رجائی و باهنر (1360 هـ.ش)","is_off"=>false],
                "d_10"=>["desc"=>"روز بانکداری اسلامی ، سالروز تصویب قانون عملیات بانکی بدون ربا (1362 هـ.ش)","is_off"=>false],
                "d_11"=>["desc"=>"روز صنعت چاپ","is_off"=>false],
                "d_12"=>["desc"=>"روز مبارزه با استعمار انگلیس","is_off"=>false],
                "d_13"=>["desc"=>"روز بزرگداشت ابوریحان بیرونی ، روز تعاون","is_off"=>false],
                "d_14"=>["desc"=>"روز اکرام - شهادت آیت الله قدوسی و سرتیب وحید دستجردی (1360 هـ.ش)","is_off"=>false],
                "d_17"=>["desc"=>"قیام 17 شهریور و کشتار جمعی به دست ماموران ستم شاهی پهلوی (1357 هـ.ش)","is_off"=>false],
                "d_19"=>["desc"=>"وفات آیت الله سید محمود طالقانی اولین امام جمه تهران (1358 ه .ش)","is_off"=>false],
                "d_20"=>["desc"=>"شهادت دومین شهید محراب آیت الله مدنی به دست منافقین (1360 هـ.ش)","is_off"=>false],
                "d_21"=>["desc"=>"روز سینما","is_off"=>false],
                "d_27"=>["desc"=>"روز بزرگداشت شهریار و شعر و ادب فارسی","is_off"=>false],
                "d_31"=>["desc"=>"آغاز جنگ تحمیلی (1359 هـ.ش) - آغاز هفته ی دفاع مقدس","is_off"=>false],

            ],
            "m_7"=>[
                "d_5"=>["desc"=>"شکست حصر آبادان در عملیات ثامن الائمه (ع) (1360 هـ.ش)","is_off"=>false],
                "d_7"=>["desc"=>"روز آتش نشانی و ایمنی","is_off"=>false],
                "d_8"=>["desc"=>"روز بزرگداشت مولوی","is_off"=>false],
                "d_9"=>["desc"=>"روز همبستگی با کودکان و نوجوانان فلسطینی","is_off"=>false],
                "d_13"=>["desc"=>"هجرت حضرت امام خمینی (ره) از عراق به پاریس (1357 هـ.ش) - روز نیروی انتظامی","is_off"=>false],
                "d_14"=>["desc"=>"روز دامپزشکی","is_off"=>false],
                "d_16"=>["desc"=>"مهر روز ، جشن مهرگان","is_off"=>false],
                "d_20"=>["desc"=>"روز بزرگداشت حافظ ، روز ملی کاهش اثرات بلایای طبیعی","is_off"=>false],
                "d_21"=>["desc"=>"جشن پیروزی کاوه و فریدون","is_off"=>false],
                "d_23"=>["desc"=>"شهادت پنجمین شهید محراب آیت الله اشرفی اصفحانی (1361 هـ.ش)","is_off"=>false],
                "d_24"=>["desc"=>"روز پیوند اولیاء و مربیان","is_off"=>false],
                "d_26"=>["desc"=>"روز تربیت بدنی و ورزش","is_off"=>false],
                "d_29"=>["desc"=>"روز صادرات","is_off"=>false],

            ],
            "m_8"=>[
                "d_1"=>["desc"=>"روز آمار و برنامه‌ریزی","is_off"=>false],
                "d_4"=>["desc"=>"اعتراض و افشاگری حضرت امام خمینی (ره) علیه پذیرش کاپیتولاسیون (1343 هـ.ش)","is_off"=>false],
                "d_8"=>["desc"=>"روز نوجوان","is_off"=>false],
                "d_10"=>["desc"=>"شهادت آیت الله قاضی طباطبائی اولین شهید محراب به دست منافقین (1358 هـ.ش) - آبان روز ، جشن آبانگان","is_off"=>false],
                "d_13"=>["desc"=>"تسخیر لانه ی جاسوسی آمریکا به دست دانشجویان (1358 هـ.ش) - روز ملی مبارزه با استکبار جهانی - روز دانش آموز","is_off"=>false],
                "d_14"=>["desc"=>"","is_off"=>false],
                "d_15"=>["desc"=>"جشن میانه پاییز","is_off"=>false],
                "d_18"=>["desc"=>"روز ملی کیفیت","is_off"=>false],
                "d_24"=>["desc"=>"روز کتاب و کتاب خوانی - روز بزرگداشت آیت الله علامه سید محمد حسین طباطبائی (1360 هـ.ش)","is_off"=>false],

            ],
            "m_9"=>[
                "d_1"=>["desc"=>"آذر جشن","is_off"=>false],
                "d_5"=>["desc"=>"روز بسیج مستضعفان ، تشکیل بسیج مستضعفان به فرمان حضرت امام خمینی (ره) (1358 هـ.ش)","is_off"=>false],
                "d_7"=>["desc"=>"روز بسیج مستضعفان ، تشکیل بسیج مستضعفان به فرمان حضرت امام خمینی (ره) (1358 هـ.ش)","is_off"=>false],
                "d_9"=>["desc"=>"روز بزرگداشت شیخ مفید - آذر روز، جشن آذرگان","is_off"=>false],
                "d_10"=>["desc"=>"شهادت آیت الله سید حسن مدرس (1316 هـ.ش) - روز مجلس","is_off"=>false],
                "d_12"=>["desc"=>"تصویب قانون اساسی جمهوری اسلامی ایران (1358 هـ.ش)","is_off"=>false],
                "d_13"=>["desc"=>"روز بیمه","is_off"=>false],
                "d_16"=>["desc"=>"روز دانشجو","is_off"=>false],
                "d_18"=>["desc"=>"معرفی عراق به عنوان مسئول و آغازگر جنگ از سوی سازمان ملل (1370 هـ.ش)","is_off"=>false],
                "d_19"=>["desc"=>"تشکیل شورای عالی انقلاب فرهنگی به فرمان حضرت امام خمینی (ره) (1363 هـ.ش)","is_off"=>false],
                "d_20"=>["desc"=>"شهادت آیت الله دستغیب سومین شهید محراب به دست منافقین (1360 هـ.ش)","is_off"=>false],
                "d_25"=>["desc"=>"روز پژوهش","is_off"=>false],
                "d_26"=>["desc"=>"روز حمل و نقل","is_off"=>false],
                "d_27"=>["desc"=>"شهادت آیت الله دکتر محمد مفتح (1358 هـ.ش) - روز وحدت حوزه و دانشگاه","is_off"=>false],
                "d_30"=>["desc"=>"جشن شب یلدا","is_off"=>false],

            ],
            "m_10"=>[
                "d_1"=>["desc"=>"روز میلاد خورشید ، جشن خرم روز، نخستین جشن دیگان","is_off"=>false],
                "d_7"=>["desc"=>"سالروز تشکیل نهضت سواد آموزی (1358 هـ.ق)","is_off"=>false],
                "d_8"=>["desc"=>"دی به آذر روز، دومین جشن دیگان","is_off"=>false],
                "d_9"=>["desc"=>"روز بصیرت و میثاق امت با ولایت (سالروز حماسه ی ملّت در 9 دی پس از فتنه ی 1388 هـ.ش)","is_off"=>false],
                "d_14"=>["desc"=>"روز جهاد کشاورزی","is_off"=>false],
                "d_15"=>["desc"=>"روز خانواده - تکریم بازنشستگان - دی به مهر روز، سومین جشن دیگان","is_off"=>false],
                "d_17"=>["desc"=>"درگذشت مشکوک جهان پهلوان تختی","is_off"=>false],
                "d_19"=>["desc"=>"قیام خونین مردم قم (1356 هـ.ش) - آغاز عملیات کربلای 5 (1365 هـ.ش)","is_off"=>false],
                "d_20"=>["desc"=>"شهادت میرزاتقی خان امیر کبیر (1230 هـ.ش)","is_off"=>false],
                "d_22"=>["desc"=>"تشکیل شورای انقلاب به فرمان حضرت امام خمینی (ره) (1357 هـ.ش)","is_off"=>true],
                "d_23"=>["desc"=>"دی به دین روز ، چهارمین جشن دیگان","is_off"=>false],
                "d_26"=>["desc"=>"فرار شاه معدوم (1357 هـ.ش)","is_off"=>false],
                "d_29"=>["desc"=>"","is_off"=>false],

            ],
            "m_11"=>[
                "d_1"=>["desc"=>"زادروز فردوسی","is_off"=>false],
                "d_2"=>["desc"=>"بهمن روز، جشن بهمنگان","is_off"=>false],
                "d_5"=>["desc"=>"جشن نوسره","is_off"=>false],
                "d_12"=>["desc"=>"بازگشت حضرت امام خمینی (ره) به ایران (1357) - آغاز دهه مبارک فجر انقلاب اسلامی","is_off"=>false],
                "d_15"=>["desc"=>"جشن میانه زمستان","is_off"=>false],
                "d_18"=>["desc"=>"روز ملی فناوری فضایی","is_off"=>false],
                "d_19"=>["desc"=>"روز نیروی هوایی","is_off"=>false],
                "d_22"=>["desc"=>"پیروزی انقلاب اسلامی و سقوط نظام شاهنشاهی (1357 هـ.ش) (تعطیل)","is_off"=>false],
                "d_29"=>["desc"=>"قیام مردم تبریز به مناسبت چهلمین روز شهادت شهدای قم (1356 هـ.ش) - جشن سپندارمذگان ، روز عشق","is_off"=>false],

            ],
            "m_12"=>[
                "d_5"=>["desc"=>"روز بزرگداشت خواجه نصیرالدین طوسی - جشن اسفندگان","is_off"=>false],
                "d_8"=>["desc"=>"روز امور تربیتی و تربیت اسلامی","is_off"=>false],
                "d_9"=>["desc"=>"روز حمایت از حقوق مصرف کنندگان","is_off"=>false],
                "d_14"=>["desc"=>"روز احسان و نیکوکاری","is_off"=>false],
                "d_15"=>["desc"=>"روز درختکاری","is_off"=>false],
                "d_16"=>["desc"=>"روز وقف","is_off"=>false],
                "d_22"=>["desc"=>"روز بزرگداشت شهدا","is_off"=>false],
                "d_25"=>["desc"=>"بمباران شیمیایی حلبچه توسط ارتش بعثی عراق (1366 هـ.ق) - روز بزرگداشت پروین اعتصامی - پایان سرایش شاهنامه","is_off"=>false],
                "d_29"=>["desc"=>"روز ملی شدن صنعت نفت ایران (1329 هـ.ش) (تعطیل)","is_off"=>true],

            ]
        ];
        $hejri_occasions = [
            "m_1"=>[
               "d_1"=>["desc"=>"آغاز سال هجری قمری","is_off"=>false],
               "d_9"=>["desc"=>"تاسوعای حسینی (تعطیل)","is_off"=>true],
               "d_10"=>["desc"=>"عاشورای حسینی (تعطیل)","is_off"=>true],
               "d_11"=>["desc"=>"روز تجلیل از اسرا و مفقودان","is_off"=>false],
               "d_12"=>["desc"=>"شهادت حضرت امام زین العابدین (ع) (95 هـ.ق)","is_off"=>false],
               "d_18"=>["desc"=>"تغییر قبله مسلمین از بیت المقدس به مکه معظمه (52 هـ.ق)","is_off"=>false],
               "d_25"=>["desc"=>"شهادت حضرت امام زین العابدین (ع) (95 هـ.ق) به روایتی","is_off"=>false],

            ],
            "m_2"=>[
                "d_3"=>["desc"=>"ولادت حضرت امام محمد باقر (ع) (57 هـ.ق)","is_off"=>false],
                "d_7"=>["desc"=>"ولادت حضرت امام موسی کاظم (ع) (128 هـ.ق)","is_off"=>false],
                "d_20"=>["desc"=>"اربعین حسینی (تعطیل)","is_off"=>true],
                "d_28"=>["desc"=>"رحلت حضرت رسول اکرم (ص) (11 هـ.ق) - شهادت حضرت امام حسن مجتبی (ع) (50 هـ.ق) (تعطیل)","is_off"=>true],
                "d_29"=>["desc"=>"شهادت حضرت امام رضا (ع) (203 هـ.ق) (تعطیل)","is_off"=>true],

            ],
            "m_3"=>[
                "d_1"=>["desc"=>"هجرت حضرت رسول اکرم (ص) از مکه به مدینه (اول محرم سال هجرت ، مبدا گاه شماری هجری قمری)","is_off"=>false],
                "d_8"=>["desc"=>"شهادت حضرت امام حسن عسگری (ع) (260 هـ.ق) و آغاز ولایت حضرت ولی‌عصر(عج)","is_off"=>true],
                "d_12"=>["desc"=>"میلاد حضرت رسول اکرم (ص) به روایت اهل سنت (53 سال قبل از هجرت) - آغاز هفته ی وحدت","is_off"=>false],
                "d_17"=>["desc"=>"میلاد حضرت رسول اکرم (ص) (53 سال قبل از هجرت) - میلاد حضرت امام جعفر صادق (ع) (تعطیل)","is_off"=>true],

            ],
            "m_4"=>[
                "d_8"=>["desc"=>"ولادت حضرت امام حسن عسگری (ع) (232 هـ.ق)","is_off"=>false],
                "d_10"=>["desc"=>"وفات حضرت معصومه (س) (201 هـ.ق)","is_off"=>false],

            ],
            "m_5"=>[
                "d_5"=>["desc"=>"ولادت حضرت زینب (س) (5 هـ.ق) - روز پرستار","is_off"=>false],
                "d_13"=>["desc"=>"شهادت حضرت فاطمه زهرا (س) (11 هـ.ق) به روایتی","is_off"=>false],

            ],
            "m_6"=>[
                "d_3"=>["desc"=>"شهادت حضرت فاطمه زهرا (س) (11 هـ.ق) (تعطیل)","is_off"=>true],
                "d_20"=>["desc"=>"ولادت حضرت فاطمه زهرا (س) (8 سال قبل از هجرت) - روز زن","is_off"=>false],

            ],
            "m_7"=>[
                "d_1"=>["desc"=>"ولادت حضرت امام محمد باقر (ع) (57 هـ.ق)","is_off"=>false],
                "d_3"=>["desc"=>"شهادت امام علی النقی الهادی (ع) (254 هـ.ق)","is_off"=>false],
                "d_10"=>["desc"=>"ولادت حضرت امام محمد تقی (ع) (جواد الائمه) (195 هـ.ق)","is_off"=>false],
                "d_13"=>["desc"=>"ولادت حضرت امام علی (ع) (23 سال قبل از هجرت) - آغاز ایام البیض (اعتکاف) (تعطیل)","is_off"=>true],
                "d_15"=>["desc"=>"وفات حضرت زینب (س) (62 هـ.ق)","is_off"=>false],
                "d_25"=>["desc"=>"شهادت امام موسی کاظم (ع) (183 هـ.ق)","is_off"=>false],
                "d_27"=>["desc"=>"مبعث رسول اکرم (ص) (13 سال قبل از هجرت) (تعطیل)","is_off"=>true],

            ],
            "m_8"=>[
                "d_3"=>["desc"=>"ولادت حضرت امام حسین (ع) (4 هـ.ق) - روز پاسدار","is_off"=>false],
                "d_4"=>["desc"=>"ولادت حضرت ابوالفضل (ع) (26 هـ.ق) - روز جانباز","is_off"=>false],
                "d_5"=>["desc"=>"ولادت حضرت امام زین العابدین (ع) (381 هـ.ق)","is_off"=>false],
                "d_11"=>["desc"=>"ولادت حضرت علی اکبر (ع) (33 هـ.ق) - روز جوان","is_off"=>false],
                "d_15"=>["desc"=>"ولادت حضرت قائم (عج) (255 هـ.ق) - روز جهانی مستضعفان (تعطیل)","is_off"=>true],

            ],
            "m_9"=>[
                "d_10"=>["desc"=>"وفات حضرت خدیجه (س) (3 سال قبل از هجرت)","is_off"=>false],
                "d_15"=>["desc"=>"ولادت حضرت امام حسن مجتبی (ع) (3 هـ.ق)","is_off"=>false],
                "d_18"=>["desc"=>"شب قدر","is_off"=>false],
                "d_19"=>["desc"=>"ضربت خوردن حضرت علی (ع) (40 هـ.ق)","is_off"=>false],
                "d_20"=>["desc"=>"شب قدر","is_off"=>false],
                "d_21"=>["desc"=>"شهادت حضرت علی (ع) (40 هـ.ق) (تعطیل)","is_off"=>true],
                "d_22"=>["desc"=>"شب قدر","is_off"=>false],

            ],
            "m_10"=>[
                "d_1"=>["desc"=>"عید سعید فطر (تعطیل)","is_off"=>true],
                "d_25"=>["desc"=>"شهادت حضرت امام جعفر صادق (ع) (148 هـ.ق) (تعطیل)","is_off"=>true],

            ],
            "m_11"=>[
                "d_1"=>["desc"=>"ولادت حضرت معصومه (س) (173 هـ.ق) - روز دختر","is_off"=>false],
                "d_11"=>["desc"=>"ولادت حضرت امام رضا (ع) (148 هـ.ق)","is_off"=>false],
                "d_30"=>["desc"=>"شهادت حضرت امام محمد تقی (ع) (جواد الائمه)","is_off"=>false],

            ],
            "m_12"=>[
                "d_1"=>["desc"=>"سالروز ازدواج حضرت علی (ع) و حضرت فاطمه (س) (2 هـ.ق) - روز ازدواج و خانواده","is_off"=>false],
                "d_7"=>["desc"=>"شهادت حضرت امام محمد باقر (ع) (114 هـ.ق)","is_off"=>false],
                "d_10"=>["desc"=>"عید سعید قربان (تعطیل)","is_off"=>true],
                "d_15"=>["desc"=>"ولادت امام علی النقی الهادی (ع) (212 هـ.ق)","is_off"=>false],
                "d_18"=>["desc"=>"عید سعید غدیر خم (10 هـ.ق) (تعطیل)","is_off"=>false],
                "d_24"=>["desc"=>"روز مباهله پیامبر اسلام (ص)","is_off"=>false],

            ],

        ];
        $miladi_occasions = [
            "m_1"=>[
              "d_1"=>["desc"=>"آغاز سال میلادی","is_off"=>false],

            ],
            "m_2"=>[
                "d_1"=>["desc"=>"روز جهانی حجاب (روز همبستگی با بانوان محجّبه)","is_off"=>false],

            ],
            "m_3"=>[
                "d_8"=>["desc"=>"روز جهانی زن","is_off"=>false],
                "d_22"=>["desc"=>"روز جهانی آب","is_off"=>false],
                "d_23"=>["desc"=>"روز جهانی هواشناسی","is_off"=>false],

            ],
            "m_4"=>[
                "d_7"=>["desc"=>"روز سلامتی (روز جهانی بهداشت)","is_off"=>false],

            ],
            "m_5"=>[
                "d_1"=>["desc"=>"وز جهانی کار و کارگر","is_off"=>false],
                "d_5"=>["desc"=>"روز جهانی ماما","is_off"=>false],
                "d_8"=>["desc"=>"روز جهانی صلیب سرخ و هلال احمر","is_off"=>false],
                "d_15"=>["desc"=>"روز جهانی خانواده","is_off"=>false],
                "d_17"=>["desc"=>"روز جهانی ارتباطات","is_off"=>false],
                "d_18"=>["desc"=>"روز جهانی موزه و میراث فرهنگی","is_off"=>false],
                "d_21"=>["desc"=>"روز جهانی توسعه فرهنگی","is_off"=>false],
                "d_31"=>["desc"=>"روز جهانی بدون دخانیات","is_off"=>false],
            ],
            "m_7"=>[
                "d_5"=>["desc"=>"روز جهانی محیط زیست","is_off"=>false],
                "d_10"=>["desc"=>"","is_off"=>false],
                "d_17"=>["desc"=>"روز جهانی بیابان زدایی","is_off"=>false],
                "d_26"=>["desc"=>"روز جهانی مبارزه با مواد مخدر","is_off"=>false],

            ],
            "m_8"=>[
                "d_1"=>["desc"=>"روز جهانی شیر مادر","is_off"=>false],
                "d_21"=>["desc"=>"روز جهانی مسجد","is_off"=>false],

            ],
            "m_9"=>[
                "d_27"=>["desc"=>"روز جهانی جهانگردی","is_off"=>false],
                "d_30"=>["desc"=>"روز جهانی ناشنوایان ، روز جهانی دریا نوردی","is_off"=>false],

            ],
            "m_10"=>[
                "d_1"=>["desc"=>"روز جهانی سالمندان","is_off"=>false],
                "d_8"=>["desc"=>"روز جهانی کودک","is_off"=>false],
                "d_9"=>["desc"=>"روز جهانی پست","is_off"=>false],
                "d_14"=>["desc"=>"روز جهانی استاندارد","is_off"=>false],
                "d_15"=>["desc"=>"روز جهانی نابینایان (عصای سفید)","is_off"=>false],
                "d_16"=>["desc"=>"روز جهانی غذا","is_off"=>false],
                "d_29"=>["desc"=>"روز جهانی بزرگداشت کورش کبیر","is_off"=>false],
            ],
            "m_12"=>[
                "d_1"=>["desc"=>"روز جهانی مبارزه با ایدز","is_off"=>false],
                "d_3"=>["desc"=>"روز جهانی معلولان","is_off"=>false],
                "d_7"=>["desc"=>"روز جهانی هواپیمایی","is_off"=>false],
                "d_25"=>["desc"=>"میلاد حضرت مسیح (ع)","is_off"=>false],

            ],
        ];
        if($format=="s")
            if(array_key_exists($day,$shamsi_occasions[$month])){
                return $shamsi_occasions[$month][$day];
            }
            else{
                return ["desc"=>"","is_off"=>false];
            }

        if($format=="m")
            if(array_key_exists($day,$miladi_occasions[$month])){
                return $miladi_occasions[$month][$day];
            }
            else{
                return ["desc"=>"","is_off"=>false];
            }
        if($format=="h")
            if(array_key_exists($day,$hejri_occasions[$month])){
                return $hejri_occasions[$month][$day];
            }
            else{
                return ["desc"=>"","is_off"=>false];
            }

    }
}
//End class bn_parsidate

/*
* parsidate function
*/
/**
 * parsidate()
 * convert gregorian datetime to persian datetime
 *
 * @param mixed $input
 * @param string $datetime
 * @param string $lang
 *
 * @return datetime
 */
function parsidate_custom( $input, $datetime = 'now', $lang = 'per' ) {
    $bndate = bn_parsidate_custom::getInstance();
    $bndate = $bndate->persian_date( $input, $datetime, $lang );

    return $bndate;
}

/**
 * gregdate()
 * convert persian datetime to gregorian datetime
 *
 * @param mixed $input
 * @param mixed $datetime
 *
 * @return datetime
 */
function gregdate_custom( $input, $datetime ) {
    $bndate = bn_parsidate_custom::getInstance();
    $bndate = $bndate->gregurian_date( $input, $datetime );

    return $bndate;
}

/**
 * convert persian number to latin
 *
 * @param string $string
 *   string that we want change number format
 *
 * @return formated string
 */
function ta_latin_num($string) {
    //arrays of persian and latin numbers
    $persian_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $latin_num = range(0, 9);

    $string = str_replace($persian_num, $latin_num, $string);

    return $string;
}

/**
 * generate milady and hejri date string
 * @param date
 * @param format
 *
 * @return formated string
 */
function getMiladyAndHejriDateString($format,$jthisYear,$jthisMonth,$daysinmonth){

    $pd = bn_parsidate_custom::getInstance();
    $thisyearf=$thismonthf=$thisdayf="";
    $thisyearl=$thismonthl=$thisdayl="";

    list( $thisyearf, $thismonthf, $thisdayf ) = $pd->persian_to_gregorian( $jthisYear, $jthisMonth, 1 );
    list( $thisyearl, $thismonthl, $thisdayl ) = $pd->persian_to_gregorian( $jthisYear, $jthisMonth, $daysinmonth );

    list( $thisyearf_h, $thismonthf_h, $thisdayf_h ) = $pd->hjConvert($thisyearf,$thismonthf,$thisdayf);
    list( $thisyearl_h, $thismonthl_h, $thisdayl_h ) = $pd->hjConvert($thisyearl, $thismonthl, $thisdayl);

    $format_array = explode(":",$format);
    if($format_array[0]=="s"){
        if($format_array[1]=="m"){

             if($format_array[2] == "f"){
                return array($thisyearf,$pd->getMonthName('m',(int)$thismonthf));
            }
            elseif ($format_array[2] == "t"){
                return array($thisyearl,$pd->getMonthName('m',(int)$thismonthl));
            }
        }
        elseif ($format_array[1]=="h")   {

            if($format_array[2] == "f"){
                return array($thisyearf_h,$pd->getMonthName('h',(int)$thismonthf_h));
            }
            elseif ($format_array[2] == "t"){
                return array($thisyearl_h,$pd->getMonthName('h',(int)$thismonthl_h));
            }

        }


    }
    elseif ($format_array[0]=="d"){
        if($format_array[1]=="h"){

            if($format_array[2]=="f"){
                return array ($thisyearf_h, $thismonthf_h, $thisdayf_h );
            }
            elseif ($format_array[2]=="t"){
                return array( $thisyearl_h, $thismonthl_h, $thisdayl_h );
            }

        }
        elseif($format_array[1]=="m"){

            if($format_array[2]=="f"){
                return array( $thisyearf, $thismonthf, $thisdayf );
            }
            elseif ($format_array[2]=="t"){
                return array( $thisyearl, $thismonthl, $thisdayl );
            }

        }

    }

}

/**
 * Get Current Day
 */
function getCurrentDay(){
    $year =  ta_latin_num(parsidate_custom("Y"));
    $month  = ta_latin_num(parsidate_custom("m"));
    return array($year,$month);
}

/**Get Class Name
 *
 */
function getClassName($class_name,$is_or_not){
    return $is_or_not?" ".$class_name." ":"";
}
/**
 * Create Persian Calendar
 *
 * @author          Mobin Ghasempoor
 * @author          Parsa Kafi
 * @return          string
 */
function wpp_get_calendar_json($jy,$jm) {

    $thisyear = $jy;
    $thismonth = $jm;
    $pd = bn_parsidate_custom::getInstance();


//    $week_begins  = intval( get_option( 'start_of_week' ) );
    $week_begins=6; // For Test

    if ( ! empty( $jm ) && ! empty( $jy ) ) {
        $thismonth = '' . zeroise( intval( $jm ), 2 );
        $thisyear  = '' . intval( $jy );

    }

    $gdate      = $pd->persian_to_gregorian( $thisyear, $thismonth, 1 );
    $unixmonth  = mktime( 0, 0, 0, $gdate[1], $gdate[2], $gdate[0] );
    $jthisyear  = $thisyear;
    $jthismonth = $thismonth;

    $jnextmonth = $jthismonth + 1;
    $jnextyear  = $jthisyear;


    if ( $jnextmonth > 12 ) {
        $jnextmonth = 1;
        $jnextyear ++;
    }


    //Create output array
    $json_calendar_output = array();
    $daysinmonth = intval( $pd->persian_date( 't', $unixmonth, 'eng' ) );

    $today_date = getCurrentDay();

    $json_calendar_output[] = ["header" => [
        "shamsi"=>[
            "data-sstring"=>$pd->persian_month_names[ (int) $jthismonth ] . ' '. $pd->persian_date( 'Y', $unixmonth ),
            "data-syear"=>ta_latin_num($jthisyear),
            "data-smonth"=>ta_latin_num($jthismonth)

        ],
        "milady"=>[
            "data-mstring-month-from"=>getMiladyAndHejriDateString("s:m:f",$jthisyear,$jthismonth,$daysinmonth)[1],
            "data-mstring-month-to"=>getMiladyAndHejriDateString("s:m:t",$jthisyear,$jthismonth,$daysinmonth)[1],
            "data-myear-from"=>getMiladyAndHejriDateString("d:m:f",$jthisyear,$jthismonth,$daysinmonth)[0],
            "data-myear-to"=>getMiladyAndHejriDateString("d:m:t",$jthisyear,$jthismonth,$daysinmonth)[0],
            "data-mmonth-from"=>getMiladyAndHejriDateString("d:m:f",$jthisyear,$jthismonth,$daysinmonth)[1],
            "data-mmonth-to"=>getMiladyAndHejriDateString("d:m:t",$jthisyear,$jthismonth,$daysinmonth)[1],

        ],
        "hejri"=>[
            "data-hstring-month-from"=>getMiladyAndHejriDateString("s:h:f",$jthisyear,$jthismonth,$daysinmonth)[1],
            "data-hstring-month-to"=>getMiladyAndHejriDateString("s:h:t",$jthisyear,$jthismonth,$daysinmonth)[1],
            "data-hyear-from"=>getMiladyAndHejriDateString("d:h:f",$jthisyear,$jthismonth,$daysinmonth)[0],
            "data-hyear-to"=>getMiladyAndHejriDateString("d:h:t",$jthisyear,$jthismonth,$daysinmonth)[0],
            "data-hmonth-from"=>getMiladyAndHejriDateString("d:h:f",$jthisyear,$jthismonth,$daysinmonth)[1],
            "data-hmonth-to"=>getMiladyAndHejriDateString("d:h:t",$jthisyear,$jthismonth,$daysinmonth)[1],

        ],
        "today"=>[
            "year"=>$today_date[0],
            "month"=>$today_date[1],
            ]

    ]
    ];

    $myweek = array();
    for ( $wdcount = 0; $wdcount <= 6; $wdcount ++ ) {
        $myweek[] = $pd->persian_day_small[ ( $wdcount + $week_begins ) % 7 ];
    }

    $json_calendar_output[] = ["day_names" => $myweek];




    $previous_month = $jthismonth - 1;
    $previous_year  = $jthisyear;
    if ( $previous_month == 0 ) {
        $previous_month = 12;
        $previous_year --;
    }

    $prev_link = ["year"=>$previous_year,"month"=>$previous_month,"month_name"=>$pd->persian_month_names[ $previous_month ],"colspan"=>3];
    $json_calendar_output [] = ["prevlink"=>$prev_link];

    $next_month = $jthismonth + 1;
    $next_year  = $jthisyear;
    if ( $next_month == 13 ) {
        $next_month = 1;
        $next_year ++;
    }

    $next_link = ["year"=>$next_year,"month"=>$next_month,"month_name"=>$pd->persian_month_names[ $next_month ]];




    $pd  = bn_parsidate_custom::getInstance();
    $pad = $pd->persian_date( "w", $pd->gregurian_date( "Y-m-d", $jthisyear . "-" . $jthismonth . "-01" ), "eng" );

    if ( 0 != $pad ) {

        $next_link["first_pad"]=$pad;
    }
    $json_calendar_output [] = ["nextlink"=>$next_link];


    $my_new_row = array();
    $row_number = 1;
    $daysinmonth = intval( $pd->persian_date( 't', $unixmonth, 'eng' ) );

    for ( $day = 1; $day <= $daysinmonth; ++ $day ) {
        list( $thisyear, $thismonth, $thisday ) = $pd->persian_to_gregorian( $jthisyear, $jthismonth, $day );
        list( $hthisyear, $hthismonth, $hthisday ) = $pd->hjConvert( $thisyear, $thismonth, $thisday );

        if ( isset( $friday ) && $friday ) {
            $json_calendar_output[] = ["week"=>$my_new_row];
            $my_new_row = [];
            $row_number++;
        }
        $friday = false;
        $today = false;
        if ( 6 == calendar_week_mod( $pd->gregurian_date( 'w', "$jthisyear-$jthismonth-$day" ) - $week_begins ) ) {
            $friday = true;
        }
        if ( $thisday == gmdate( 'j', ( time() + ( get_option( 'gmt_offset' ) * 3600 ) ) ) && $thismonth == gmdate( 'm', time() + ( get_option( 'gmt_offset' ) * 3600 ) ) && $thisyear == gmdate( 'Y', time() + ( get_option( 'gmt_offset' ) * 3600 ) ) ) {
            $today = true;
        }

        $my_new_row[] = [
            "day"=>[
                "data-sday"=>$day,
                "is_today"=>$today,
                "is_friday"=>$friday,
                "data-sday-ocassion-desc"=>$pd->getDateoccasions("s","m_". intval($jthismonth),"d_".$day)["desc"],
                "data-sday-ocassion-is-off"=>$pd->getDateoccasions("s","m_".intval($jthismonth),"d_".$day)["is_off"],
                "data-myear"=>$thisyear,
                "data-mmonth"=>$thismonth,
                "data-mday"=>$thisday,
                "data-mday-ocassion-desc"=>$pd->getDateoccasions("m","m_".$thismonth,"d_".$thisday)["desc"],
                "data-mday-ocassion-is-off"=>$pd->getDateoccasions("m","m_".$thismonth,"d_".$thisday)["is_off"],
                "data-hyear"=>$hthisyear,
                "data-hmonth"=>$hthismonth,
                "data-hday"=>$hthisday,
                "data-hday-ocassion-desc"=>$pd->getDateoccasions("h","m_".$hthismonth,"d_".$hthisday)["desc"],
                "data-hday-ocassion-is-off"=>$pd->getDateoccasions("h","m_".$hthismonth,"d_".$hthisday)["is_off"],

            ]

        ];
    }

    $last_row = ["week"=>$my_new_row];

    $pad = 7 - calendar_week_mod( $pd->gregurian_date( 'w', "$jthisyear-$jthismonth-$day", 'eng' ) - $week_begins );
    if ( $pad != 0 && $pad != 7 ) {
        $last_row["last_pad"] = ["last_pad" =>$pad];
    }
    $json_calendar_output[] = $last_row;

    return json_encode($json_calendar_output);
}


/**
 * Create Persian Calendar
 *
 * @author          Mobin Ghasempoor
 * @author          Parsa Kafi
 * @return          string
 */
function wpp_get_calendar_custom($jy,$jm) {

    $thisyear = $jy;
    $thismonth = $jm;
    $pd = bn_parsidate_custom::getInstance();


//    $week_begins  = intval( get_option( 'start_of_week' ) );
    $week_begins=6; // For Test

    if ( ! empty( $jm ) && ! empty( $jy ) ) {
        $thismonth = '' . zeroise( intval( $jm ), 2 );
        $thisyear  = '' . intval( $jy );

    }

    $gdate      = $pd->persian_to_gregorian( $thisyear, $thismonth, 1 );
    $unixmonth  = mktime( 0, 0, 0, $gdate[1], $gdate[2], $gdate[0] );
    $jthisyear  = $thisyear;
    $jthismonth = $thismonth;

    $jnextmonth = $jthismonth + 1;
    $jnextyear  = $jthisyear;


    if ( $jnextmonth > 12 ) {
        $jnextmonth = 1;
        $jnextyear ++;
    }


    $calendar_output = '<div id="wp_persian_calendar_main">';

    $today_date = getCurrentDay();
    $daysinmonth = intval( $pd->persian_date( 't', $unixmonth, 'eng' ) );

    $calendar_output = $calendar_output .
        '<table id="wp_persian_calendar_table">
        <caption>';




    $calendar_output = $calendar_output ."<div style='height: 24px' class='shamsi_header'><span style='width: 20%;float: right' class=\"dashicons dashicons-arrow-down-alt month_select_icon\"></span> <div class='persian' style='width: 50%;float: right' id='wp_persian_tbl_caption_shamsi'";
    $calendar_output = $calendar_output ." data-syear='".ta_latin_num($jthisyear)."' data-smonth='". ta_latin_num($jthismonth);
    $calendar_output = $calendar_output ."' data-current-year='".intval($today_date[0])
    ." 'data-current-month='".intval($today_date[1])."'> "
    .$pd->persian_month_names[ (int) $jthismonth ] . ' '. $pd->persian_date( 'Y', $unixmonth )
    ."</div><span style='width: 30%;float: right' class=\"dashicons dashicons-arrow-down-alt year_select_icon \"></span></div>";




    $calendar_output.= "<div class='milady_header'><div class='english' id='wp_persian_tbl_caption_miladi_month_from' data-mmonth-from='"
    .getMiladyAndHejriDateString("d:m:f",$jthisyear,$jthismonth,$daysinmonth)[1]
    ."'>"
    .getMiladyAndHejriDateString("s:m:f",$jthisyear,$jthismonth,$daysinmonth)[1]
    ."</div>";


    if(getMiladyAndHejriDateString("d:m:f",$jthisyear,$jthismonth,$daysinmonth)[0]
        == getMiladyAndHejriDateString("d:m:t",$jthisyear,$jthismonth,$daysinmonth)[0]){
        $calendar_output = $calendar_output  ."<div class='english' style=\"width:44%\" id='wp_persian_tbl_caption_miladi_month_to' data-mmonth-to='".
            getMiladyAndHejriDateString("d:m:t",$jthisyear,$jthismonth,$daysinmonth)[1]
            ."'>"
            .getMiladyAndHejriDateString("s:m:t",$jthisyear,$jthismonth,$daysinmonth)[1]
            ."</div>";
    }
    $calendar_output = $calendar_output . "<div class='english' id='wp_persian_tbl_caption_miladi_year_from' data-myear-from='".
        getMiladyAndHejriDateString("d:m:f",$jthisyear,$jthismonth,$daysinmonth)[0]
        . "' >"
        .getMiladyAndHejriDateString("d:m:f",$jthisyear,$jthismonth,$daysinmonth)[0]
        ."</div>";
    if(getMiladyAndHejriDateString("d:m:f",$jthisyear,$jthismonth,$daysinmonth)[0]
        != getMiladyAndHejriDateString("d:m:t",$jthisyear,$jthismonth,$daysinmonth)[0]){
        $calendar_output = $calendar_output  ."<div  class='english' style=\"width:25%\" id='wp_persian_tbl_caption_miladi_month_to' data-mmonth-to='"
            .getMiladyAndHejriDateString("d:m:t",$jthisyear,$jthismonth,$daysinmonth)[1]
            ."'>"
            .getMiladyAndHejriDateString("s:m:t",$jthisyear,$jthismonth,$daysinmonth)[1]
            ."</div>";
        $calendar_output = $calendar_output  ."<div class='english' id='wp_persian_tbl_caption_miladi_year_to' data-myear-to='".
            getMiladyAndHejriDateString("d:m:t",$jthisyear,$jthismonth,$daysinmonth)[0]
            ."'>"
            .getMiladyAndHejriDateString("d:m:t",$jthisyear,$jthismonth,$daysinmonth)[0]
            ."</div>";
    }
    $calendar_output = $calendar_output."</div>";



    $calendar_output = $calendar_output . "<div class='hejri_header'><div class='arabic' id='wp_persian_tbl_caption_hejri_month_from' data-hmonth-from='"
        .getMiladyAndHejriDateString("d:h:f",$jthisyear,$jthismonth,$daysinmonth)[1]

        ."'>"
        .getMiladyAndHejriDateString("s:h:f",$jthisyear,$jthismonth,$daysinmonth)[1]
        ."</div>";
    if(getMiladyAndHejriDateString("d:h:f",$jthisyear,$jthismonth,$daysinmonth)[0]
        == getMiladyAndHejriDateString("d:h:t",$jthisyear,$jthismonth,$daysinmonth)[0]){
        $calendar_output = $calendar_output ."<div style=\"width:44%\" class='arabic' id='wp_persian_tbl_caption_hejri_month_to' data-hmonth-to='"
            .getMiladyAndHejriDateString("d:h:t",$jthisyear,$jthismonth,$daysinmonth)[1]

            ."'>"
            .getMiladyAndHejriDateString("s:h:t",$jthisyear,$jthismonth,$daysinmonth)[1]
            ."</div>";
    }
    $calendar_output = $calendar_output . "<div class='arabic' id='wp_persian_tbl_caption_hejri_year_from' data-hyear-from='".
        getMiladyAndHejriDateString("d:h:f",$jthisyear,$jthismonth,$daysinmonth)[0]
        . "' >"
        .getMiladyAndHejriDateString("d:h:f",$jthisyear,$jthismonth,$daysinmonth)[0]
        ."</div>";
    if(getMiladyAndHejriDateString("d:h:f",$jthisyear,$jthismonth,$daysinmonth)[0]
        != getMiladyAndHejriDateString("d:h:t",$jthisyear,$jthismonth,$daysinmonth)[0]){
        $calendar_output = $calendar_output ."<div style=\"width:25%\" class='arabic' id='wp_persian_tbl_caption_hejri_month_to' data-hmonth-to='"
            .getMiladyAndHejriDateString("d:h:f",$jthisyear,$jthismonth,$daysinmonth)[1]

            ."'>"
            .getMiladyAndHejriDateString("s:h:t",$jthisyear,$jthismonth,$daysinmonth)[1]
            ."</div>";
        $calendar_output = $calendar_output ."<div class='arabic' id='wp_persian_tbl_caption_hejri_year_to' data-hyear-to='".
            getMiladyAndHejriDateString("d:h:t",$jthisyear,$jthismonth,$daysinmonth)[0]
            ."'>"
            .getMiladyAndHejriDateString("d:h:t",$jthisyear,$jthismonth,$daysinmonth)[0]
            ."</div>";
    }




    $calendar_output .= '</div></caption>
        <thead>
        <tr>';
    $myweek = array();
    for ( $wdcount = 0; $wdcount <= 6; $wdcount ++ ) {
        $myweek[] = $pd->persian_day_small[ ( $wdcount + $week_begins ) % 7 ];
    }

    foreach ( $myweek as $wd ) {
        $calendar_output .= "\n\t\t<th scope=\"col\" title=\"$wd\">$wd</th>";
    }

    $calendar_output .= '
        </tr>
        </thead>

        <tfoot>
       <tr>';


    $previous_month = $jthismonth - 1;
    $previous_year  = $jthisyear;
    if ( $previous_month == 0 ) {
        $previous_month = 12;
        $previous_year --;
    }

    $colspan_footer = 2;
      //اصلاح لینک
    $calendar_output .= "\n\t\t" . '<td colspan="'.$colspan_footer.'" id="prev"><a id="prev_month" data-in="'.$previous_year.'" data-out="'.$previous_month.'" href="#">&laquo; ' . $pd->persian_month_names[ $previous_month ] . '</a></td>';


    $calendar_output .= "\n\t\t" . '<td colspan="3" class="pad"><button id=\'today_button\' class=\'button-primary\'>امروز</button></td>';

    if ( true ) {
        $next_month = $jthismonth + 1;
        $next_year  = $jthisyear;
        if ( $next_month == 13 ) {
            $next_month = 1;
            $next_year ++;
        }
        // اصلاح لینک
        $calendar_output .= "\n\t\t" . '<td colspan="'.$colspan_footer.'" id="next"><a id="next_month" data-in="'.$next_year.'" data-out="'.$next_month.'" href="#">' . $pd->persian_month_names[ $next_month ] . ' &raquo;</a></td>';
    }

    $calendar_output .= '
            </tr>
            </tfoot>

            <tbody>
            <tr>';

    //____________________________________________________________________________________________________________________________________


    $pd  = bn_parsidate_custom::getInstance();
    $pad = $pd->persian_date( "w", $pd->gregurian_date( "Y-m-d", $jthisyear . "-" . $jthismonth . "-01" ), "eng" );

    if ( 0 != $pad ) {
        $calendar_output .= "\n\t\t" . '<td colspan="' . $pad . '" class="pad">&nbsp;</td>';
    }

    $daysinmonth = intval( $pd->persian_date( 't', $unixmonth, 'eng' ) );

    for ( $day = 1; $day <= $daysinmonth; ++ $day ) {
        list( $thisyear, $thismonth, $thisday ) = $pd->persian_to_gregorian( $jthisyear, $jthismonth, $day );
        list( $hthisyear, $hthismonth, $hthisday ) = $pd->hjConvert( $thisyear, $thismonth, $thisday );
        if ( isset( $friday ) && $friday ) {
            $calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
        }
        $friday = false;
        $today = false;
        if ( 6 == calendar_week_mod( $pd->gregurian_date( 'w', "$jthisyear-$jthismonth-$day" ) - $week_begins ) ) {
            $friday = true;
        }
        if ( $thisday == gmdate( 'j', ( time() + ( get_option( 'gmt_offset' ) * 3600 ) ) ) && $thismonth == gmdate( 'm', time() + ( get_option( 'gmt_offset' ) * 3600 ) ) && $thisyear == gmdate( 'Y', time() + ( get_option( 'gmt_offset' ) * 3600 ) ) ) {
            $today = true;
        }
        $calendar_output .= '<td>';


        $calendar_output .="<div class='  "
            ." shamsi_day "
            ." persian "
            .getClassName(" today ",$today)

            .getClassName(" friday ",$friday)

            .getClassName(" dayoff ",$pd->getDateoccasions("h","m_".$hthismonth,"d_".$hthisday)["is_off"])

            .getClassName(" dayoff ",$pd->getDateoccasions("s","m_".intval($jthismonth),"d_".$day)["is_off"])
            ."'" .
            " data-sday='".$day."'" .
            " title='".$pd->getDateoccasions("s","m_". intval($jthismonth),"d_".$day)["desc"]."'" .
            " data-sday-ocassion-is-off='".$pd->getDateoccasions("s","m_".intval($jthismonth),"d_".$day)["is_off"]."'" .
            ">".$day."</div>";

        $calendar_output .="<div class='milady_day english "

            .getClassName("dayoff",$pd->getDateoccasions("m","m_".$thismonth,"d_".$thisday)["is_off"])

            ."' data-myear='".$thisyear."' ".
            " data-mmonth='".$thismonth."'".
            " data-mday='".$thisday."'".
            " title='".$pd->getDateoccasions("m","m_".$thismonth,"d_".$thisday)["desc"]."'".
            " data-mday-ocassion-is-off='".$pd->getDateoccasions("m","m_".$thismonth,"d_".$thisday)["is_off"]."'>".
            $thisday."</div>";

        $calendar_output .="<div class='".
            " hejri_day arabic "
            .getClassName("dayoff",$pd->getDateoccasions("h","m_".$hthismonth,"d_".$hthisday)["is_off"])
            ."'"
            ."data-hyear='".$hthisyear."' ".
            " data-hmonth='".$hthismonth."'".
            " data-hday='".$hthisday."'"
            ." title='".$pd->getDateoccasions("h","m_".$hthismonth,"d_".$hthisday)["desc"]."'".
            " data-hday-ocassion-is-off='".$pd->getDateoccasions("h","m_".$hthismonth,"d_".$hthisday)["is_off"]."'"
            .">".$hthisday."</div>";

        $calendar_output .= '</td>';

    }
    $pad = 7 - calendar_week_mod( $pd->gregurian_date( 'w', "$jthisyear-$jthismonth-$day", 'eng' ) - $week_begins );
    if ( $pad != 0 && $pad != 7 ) {
        $calendar_output .= "\n\t\t" . '<td class="pad" colspan="' . $pad . '">&nbsp;</td>';
    }

    $calendar_output =  $calendar_output . "\n\t</tr>\n\t</tbody>\n\t</table>";

    $calendar_output.='</div>';

    return $calendar_output;
}


