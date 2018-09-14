<?php 
/**
 * lunar.php
 * A Chinese calendar transform class 农历公历转换类
 * 农历公历转换，阳历转阴历，阴历转阳历，今天的阴历日期
 * @author: ionepub
 * @version 1.0
 * GitHub repo: https://github.com/ionepub/lunar
 * @date 2018-09
 */
namespace Ionepub;

/**
* class Lunar
*/
class Lunar
{
	/**
	 * 阳历定义常量
	 * @var string
	 */
	const SOLAR = 'solar';

	/**
	 * 阴历定义常量
	 * @var string
	 */
	const LUNAR = 'lunar';

	/**
	 * （汉字）阴历定义常量
	 * @var string
	 */
	const LUNAR_CN = 'lunar_cn';

	/**
	 * 单例实例
	 * @access private
	 * @var class object
	 */
	private static $_instance;

	/**
     * 阴历 1900-2100 的润大小信息
     *
     * @var array
     */
    private $lunars = [
        0x04bd8, 0x04ae0, 0x0a570, 0x054d5, 0x0d260, 0x0d950, 0x16554, 0x056a0, 0x09ad0, 0x055d2, // 1900-1909
        0x04ae0, 0x0a5b6, 0x0a4d0, 0x0d250, 0x1d255, 0x0b540, 0x0d6a0, 0x0ada2, 0x095b0, 0x14977, // 1910-1919
        0x04970, 0x0a4b0, 0x0b4b5, 0x06a50, 0x06d40, 0x1ab54, 0x02b60, 0x09570, 0x052f2, 0x04970, // 1920-1929
        0x06566, 0x0d4a0, 0x0ea50, 0x06e95, 0x05ad0, 0x02b60, 0x186e3, 0x092e0, 0x1c8d7, 0x0c950, // 1930-1939
        0x0d4a0, 0x1d8a6, 0x0b550, 0x056a0, 0x1a5b4, 0x025d0, 0x092d0, 0x0d2b2, 0x0a950, 0x0b557, // 1940-1949
        0x06ca0, 0x0b550, 0x15355, 0x04da0, 0x0a5b0, 0x14573, 0x052b0, 0x0a9a8, 0x0e950, 0x06aa0, // 1950-1959
        0x0aea6, 0x0ab50, 0x04b60, 0x0aae4, 0x0a570, 0x05260, 0x0f263, 0x0d950, 0x05b57, 0x056a0, // 1960-1969
        0x096d0, 0x04dd5, 0x04ad0, 0x0a4d0, 0x0d4d4, 0x0d250, 0x0d558, 0x0b540, 0x0b6a0, 0x195a6, // 1970-1979
        0x095b0, 0x049b0, 0x0a974, 0x0a4b0, 0x0b27a, 0x06a50, 0x06d40, 0x0af46, 0x0ab60, 0x09570, // 1980-1989
        0x04af5, 0x04970, 0x064b0, 0x074a3, 0x0ea50, 0x06b58, 0x055c0, 0x0ab60, 0x096d5, 0x092e0, // 1990-1999
        0x0c960, 0x0d954, 0x0d4a0, 0x0da50, 0x07552, 0x056a0, 0x0abb7, 0x025d0, 0x092d0, 0x0cab5, // 2000-2009
        0x0a950, 0x0b4a0, 0x0baa4, 0x0ad50, 0x055d9, 0x04ba0, 0x0a5b0, 0x15176, 0x052b0, 0x0a930, // 2010-2019
        0x07954, 0x06aa0, 0x0ad50, 0x05b52, 0x04b60, 0x0a6e6, 0x0a4e0, 0x0d260, 0x0ea65, 0x0d530, // 2020-2029
        0x05aa0, 0x076a3, 0x096d0, 0x04afb, 0x04ad0, 0x0a4d0, 0x1d0b6, 0x0d250, 0x0d520, 0x0dd45, // 2030-2039
        0x0b5a0, 0x056d0, 0x055b2, 0x049b0, 0x0a577, 0x0a4b0, 0x0aa50, 0x1b255, 0x06d20, 0x0ada0, // 2040-2049
        0x14b63, 0x09370, 0x049f8, 0x04970, 0x064b0, 0x168a6, 0x0ea50, 0x06b20, 0x1a6c4, 0x0aae0, // 2050-2059
        0x0a2e0, 0x0d2e3, 0x0c960, 0x0d557, 0x0d4a0, 0x0da50, 0x05d55, 0x056a0, 0x0a6d0, 0x055d4, // 2060-2069
        0x052d0, 0x0a9b8, 0x0a950, 0x0b4a0, 0x0b6a6, 0x0ad50, 0x055a0, 0x0aba4, 0x0a5b0, 0x052b0, // 2070-2079
        0x0b273, 0x06930, 0x07337, 0x06aa0, 0x0ad50, 0x14b55, 0x04b60, 0x0a570, 0x054e4, 0x0d160, // 2080-2089
        0x0e968, 0x0d520, 0x0daa0, 0x16aa6, 0x056d0, 0x04ae0, 0x0a9d4, 0x0a2d0, 0x0d150, 0x0f252, // 2090-2099
        0x0d520, // 2100
    ];

    /**
     * 数字转中文速查表
     *
     * @var array
     */
    private $weekdayAlias = ['日', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十'];

    /**
     * 日期转阴历称呼速查表
     *
     * @var array
     */
    private $dateAlias = ['初', '十', '廿', '卅'];

    /**
     * 月份转阴历称呼速查表
     *
     * @var array
     */
    private $monthAlias = ['正', '二', '三', '四', '五', '六', '七', '八', '九', '十', '冬', '腊'];

    /**
     * 阳历的日期数组
     * y => 年，
     * m => 月，
     * d => 日，
     * h => 时
     *
     * @var array
     */
    private $solarDate = ['y' => 0, 'm' => 0, 'd' => 0, 'h' => null];

    /**
	 * 构造函数
	 * @access private
	 */
	private function __construct(){}

	/**
	 * 返回单例实例/初始化地区列表
	 * @access public
	 * @param string $id 身份证号参数，可选，传递时设置id
	 * @return object
	 */
	public static function getInstance(){
		if(!(self::$_instance instanceof self)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}

    /**
     * 传入阳历年月日获得详细的阳历、阴历信息
     * 返回实例，通过get方法获取
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     *
     * @return object
     */
    public function solar($year = 0, $month = 0, $day = 0, $hour = null){
    	list($year, $month, $day) = $this->parseDate( $year, $month, $day );

    	$this->solarDate = [
    		'y'	=>	$year,
    		'm'	=>	$month,
    		'd'	=>	$day,
    		'h'	=>	$this->hourValidate($hour),
    	];
    	return $this;
    }

    /**
     * 传入阴历年月日以及传入的月份是否闰月获得详细的阳历、阴历信息
     * 返回实例，通过get方法获取
     *
     * @param int  $year        lunar year
     * @param int  $month       lunar month
     * @param int  $day         lunar day
     * @param bool $isLeapMonth lunar month is leap or not.[如果是阴历闰月第四个参数赋值true即可]
     * @param int  $hour        birth hour.[0~23]
     *
     * @return object
     */
    public function lunar($year = 0, $month = 0, $day = 0, $isLeapMonth = false, $hour = null){
    	if(!$year || !$month || !$day){
    		// 未传递参数，获取今天
    		return $this->solar();
    	}

    	list($year, $month, $day) = $this->parseDate( $year, $month, $day );

    	$solar = $this->lunar2solar($year, $month, $day, boolval($isLeapMonth));

    	$this->solarDate = [
    		'y'	=>	$solar['solar_year'],
    		'm'	=>	$solar['solar_month'],
    		'd'	=>	$solar['solar_day'],
    		'h'	=>	$this->hourValidate($hour),
    	];
    	return $this;
    }

    /**
     * 返回阴历阳历数据
     *
     * @return Array
	 *	(
	 *	    [year] => 阳历年
	 *	    [month] => 阳历月
	 *	    [day] => 阳历日
	 *	    [lunar_year] => 阴历年
	 *	    [lunar_month] => 阴历月
	 *	    [lunar_day] => 阴历日
	 *	    [lunar_year_chinese] => （中文）阴历年
	 *	    [lunar_month_chinese] => （中文）阴历月
	 *	    [lunar_day_chinese] => （中文）阴历日
	 *	    [is_leap] => 是否闰月
	 *	)
     */
    public function get(){
    	if(!$this->solarDate['y']){
    		throw new \InvalidArgumentException("未设置日期");
    	}

    	list($year, $month, $day, $hour) = array_values($this->solarDate);
    	
    	$lunar = $this->solar2lunar($year, $month, $day, $hour);

        return array_merge([
            'year' => sprintf('%04d', $year), // 阳历年
            'month' => sprintf('%02d', $month), // 阳历月
            'day' => sprintf('%02d', $day), // 阳历日
        ], $lunar);
    }

    /**
     * 返回阴历阳历字符串
     * 
     * @param $type 返回类型 [SOLAR阳历|LUNAR阴历|LUNAR_CN阴历中文] 默认阳历
     * @param $seperate 年月日之间的分隔符，默认-
     * @return string
     */
    public function str($type = self::SOLAR, $seperate = '-'){
    	if(!$this->solarDate['y']){
    		throw new \InvalidArgumentException("未设置日期");
    	}

    	if($type == self::LUNAR || $type == self::LUNAR_CN){
    		// 返回阴历字符串或中文阴历字符串
    		list($year, $month, $day, $hour) = array_values($this->solarDate);
    	
    		$lunar = $this->solar2lunar($year, $month, $day, $hour);

    		$lunar_arr = [
    			($type == self::LUNAR_CN) ? $lunar['lunar_year_chinese'] : $lunar['lunar_year'],
    			($type == self::LUNAR_CN) ? $lunar['lunar_month_chinese'] : $lunar['lunar_month'],
    			($type == self::LUNAR_CN) ? $lunar['lunar_day_chinese'] : $lunar['lunar_day'],
    		];
    		return implode($seperate, $lunar_arr);
    	}

    	// 默认返回阳历字符串
    	$date = $this->solarDate;
		unset($date['h']);
		return date('Y'.$seperate.'m'.$seperate.'d', strtotime( implode( '-', $date) )) ;
    }

    /**
     * 解析年月日，过滤错误参数
     *
     * @param int $year 年
     * @param int $month 月
     * @param int $day 日
     * @return array
     */
    private function parseDate($year = 0, $month = 0, $day = 0){
    	if(!$year || !$month || !$day){
    		$date = $this->makeDate(); // now
    	}else{
    		$date = $this->makeDate("{$year}-{$month}-{$day}");
    	}
    	
    	if(!$date){
    		throw new \InvalidArgumentException("日期参数错误");
    	}

    	return [
    		date_format($date, 'Y'),
    		date_format($date, 'm'),
    		date_format($date, 'd'),
    	];
    }

    /**
     * 创建日期对象
     *
     * @param string $string
     * @param string $timezone
     *
     * @return \DateTime
     */
    private function makeDate($string = 'now', $timezone = 'PRC')
    {
        // return new \DateTime($string, new \DateTimeZone($timezone));
        // 通过date_create函数创建，如果失败返回false
        return date_create($string, new \DateTimeZone($timezone));
    }

    /**
     * 获取两个日期之间的距离.
     *
     * @param string|\DateTime $date1
     * @param string|\DateTime $date2
     *
     * @return bool|\DateInterval
     */
    private function dateDiff($date1, $date2)
    {
        if (!($date1 instanceof \DateTime)) {
            $date1 = $this->makeDate($date1);
        }

        if (!($date2 instanceof \DateTime)) {
            $date2 = $this->makeDate($date2);
        }

        return $date1->diff($date2);
    }

    /**
     * 返回阴历指定年的总天数.
     *
     * @param int $year
     *
     * @return int
     */
    private function daysOfYear($year)
    {
        $sum = 348;

        for ($i = 0x8000; $i > 0x8; $i >>= 1) {
            $sum += ($this->lunars[$year - 1900] & $i) ? 1 : 0;
        }

        return $sum + $this->leapDays($year);
    }

    /**
     * 返回阴历 y 年闰月是哪个月；若 y 年没有闰月 则返回0.
     *
     * @param int $year
     *
     * @return int
     */
    private function leapMonth($year)
    {
        // 闰字编码 \u95f0
        return $this->lunars[$year - 1900] & 0xf;
    }

    /**
     * 返回阴历y年闰月的天数 若该年没有闰月则返回 0.
     *
     * @param int $year
     *
     * @return int
     */
    private function leapDays($year)
    {
        if ($this->leapMonth($year)) {
            return ($this->lunars[$year - 1900] & 0x10000) ? 30 : 29;
        }

        return 0;
    }

    /**
     * 返回阴历 y 年 m 月（非闰月）的总天数，计算 m 为闰月时的天数请使用 leapDays 方法.
     *
     * @param int $year
     * @param int $month
     *
     * @return int
     */
    private function lunarDays($year, $month)
    {
        // 月份参数从 1 至 12，参数错误返回 -1
        if ($month > 12 || $month < 1) {
            return -1;
        }

        return ($this->lunars[$year - 1900] & (0x10000 >> $month)) ? 30 : 29;
    }

    private function toChinaYear($year)
    {
        if (!is_numeric($year)) {
            throw new \InvalidArgumentException("错误的年份:{$year}");
        }
        $lunarYear = '';
        $year = (string) $year;
        for ($i = 0, $l = strlen($year); $i < $l; ++$i) {
            $lunarYear .= '0' !== $year[$i] ? $this->weekdayAlias[$year[$i]] : '零';
        }

        return $lunarYear;
    }

    /**
     * 传入阴历数字月份返回汉语通俗表示法.
     *
     * @param int $month
     *
     * @return string
     */
    private function toChinaMonth($month)
    {
        // 若参数错误 返回 -1
        if ($month > 12 || $month < 1) {
            throw new \InvalidArgumentException("错误的月份:{$month}");
        }

        return $this->monthAlias[abs($month) - 1].'月';
    }

    /**
     * 传入阴历日期数字返回汉字表示法.
     *
     * @param int $day
     *
     * @return string
     */
    private function toChinaDay($day)
    {
        switch ($day) {
            case 10:
                return '初十';
            case 20:
                return '二十';
            case 30:
                return '三十';
            default:
                return $this->dateAlias[intval($day / 10)].$this->weekdayAlias[$day % 10];
        }
    }

    /**
     * 阳历转阴历.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     *
     * @return array
     */
    private function solar2lunar($year, $month, $day, $hour = null)
    {
        if (23 == $hour) {
            // 23点过后算子时，阴历以子时为一天的起始
            $day += 1;
        }
        $date = $this->makeDate("{$year}-{$month}-{$day}");

        list($year, $month, $day) = explode('-', $date->format('Y-n-j'));

        // 参数区间1900.1.31~2100.12.31
        if ($year < 1900 || $year > 2100) {
            throw new \InvalidArgumentException("不支持的年份:{$year}");
        }

        // 年份限定、上限
        if (1900 == $year && 1 == $month && $day < 31) {
            throw new \InvalidArgumentException("不支持的日期:{$year}-{$month}-{$day}");
        }

        $offset = $this->dateDiff($date, '1900-01-31')->days;

        for ($i = 1900; $i < 2101 && $offset > 0; ++$i) {
            $daysOfYear = $this->daysOfYear($i);
            $offset -= $daysOfYear;
        }

        if ($offset < 0) {
            $offset += $daysOfYear;
            --$i;
        }

        // 阴历年
        $lunarYear = $i;

        $leap = $this->leapMonth($i); // 闰哪个月
        $isLeap = false;

        // 用当年的天数 offset,逐个减去每月（阴历）的天数，求出当天是本月的第几天
        for ($i = 1; $i < 13 && $offset > 0; ++$i) {
            // 闰月
            if ($leap > 0 && $i == ($leap + 1) && !$isLeap) {
                --$i;
                $isLeap = true;
                $daysOfMonth = $this->leapDays($lunarYear); // 计算阴历月天数
            } else {
                $daysOfMonth = $this->lunarDays($lunarYear, $i); // 计算阴历普通月天数
            }

            // 解除闰月
            if (true === $isLeap && $i == ($leap + 1)) {
                $isLeap = false;
            }

            $offset -= $daysOfMonth;
        }
        // offset为0时，并且刚才计算的月份是闰月，要校正
        if (0 === $offset && $leap > 0 && $i == $leap + 1) {
            if ($isLeap) {
                $isLeap = false;
            } else {
                $isLeap = true;
                --$i;
            }
        }

        if ($offset < 0) {
            $offset += $daysOfMonth;
            --$i;
        }

        // 阴历月
        $lunarMonth = $i;

        // 阴历日
        $lunarDay = $offset + 1;

        return [
            'lunar_year' => (string) $lunarYear,  // 阴历年
            'lunar_month' => sprintf('%02d', $lunarMonth),  // 阴历月
            'lunar_day' => sprintf('%02d', $lunarDay),  // 阴历日
            'lunar_year_chinese' => $this->toChinaYear($lunarYear),  // 阴历年中文
            'lunar_month_chinese' => ($isLeap ? '闰' : '').$this->toChinaMonth($lunarMonth), // 阴历月中文
            'lunar_day_chinese' => $this->toChinaDay($lunarDay),  // 阴历日中文
            'is_leap' => $isLeap,  // 是否闰月
        ];
    }

    /**
     * 阴历转阳历.
     *
     * @param int  $year
     * @param int  $month
     * @param int  $day
     * @param bool $isLeapMonth
     *
     * @return array|int
     */
    private function lunar2solar($year, $month, $day, $isLeapMonth = false)
    {
        // 参数区间 1900.1.3 1 ~2100.12.1
        $leapMonth = $this->leapMonth($year);

        // 传参要求计算该闰月阳历 但该年得出的闰月与传参的月份并不同
        if ($isLeapMonth && ($leapMonth != $month)) {
            $isLeapMonth = false;
        }

        // 超出了最大极限值
        if (2100 == $year && 12 == $month && $day > 1 || 1900 == $year && 1 == $month && $day < 31) {
            return -1;
        }

        $maxDays = $days = $this->lunarDays($year, $month);

        // if month is leap, _day use leapDays method
        if ($isLeapMonth) {
            $maxDays = $this->leapDays($year, $month);
        }

        // 参数合法性效验
        if ($year < 1900 || $year > 2100 || $day > $maxDays) {
            throw new \InvalidArgumentException('传入的参数不合法');
        }

        // 计算阴历的时间差
        $offset = 0;

        for ($i = 1900; $i < $year; ++$i) {
            $offset += $this->daysOfYear($i);
        }

        $isAdd = false;
        for ($i = 1; $i < $month; ++$i) {
            $leap = $this->leapMonth($year);
            if (!$isAdd) {// 处理闰月
                if ($leap <= $i && $leap > 0) {
                    $offset += $this->leapDays($year);
                    $isAdd = true;
                }
            }
            $offset += $this->lunarDays($year, $i);
        }

        // 转换闰月阴历 需补充该年闰月的前一个月的时差
        if ($isLeapMonth) {
            $offset += $days;
        }

        // 1900 年阴历正月一日的阳历时间为 1900 年 1 月 30 日 0 时 0 分 0 秒 (该时间也是本阴历的最开始起始点)
        // XXX: 部分 windows 机器不支持负时间戳，所以这里就写死了,哈哈哈哈...
        $startTimestamp = -2206483200;
        $date = date('Y-m-d', ($offset + $day) * 86400 + $startTimestamp);

        list($solarYear, $solarMonth, $solarDay) = explode('-', $date);

        return [
            'solar_year' => $solarYear,
            'solar_month' => sprintf('%02d', $solarMonth),
            'solar_day' => sprintf('%02d', $solarDay),
        ];
    }

    /**
     * 校验小时参数是否有效，如果有效原样返回，无效则返回null
     * 
     * @param int $hour
     * @return int|null
     */
    private function hourValidate($hour = null){
    	if (!is_numeric($hour) || $hour < 0 || $hour > 23) {
    		return null;
    	}
    	return $hour;
    }

}

