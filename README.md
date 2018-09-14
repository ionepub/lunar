# lunar

农历公历转换，阳历转阴历，阴历转阳历，今天的阴历日期。
A Chinese calendar transform class.

## 安装

### 使用composer安装（建议）

```
# 稳定版本
composer require ionepub/lunar
composer require --prefer-dist ionepub/lunar

# 开发版本
composer require ionepub/lunar:dev-master -vvv
```

### 直接下载

下载地址：<https://github.com/ionepub/lunar/releases>



## 使用

### 实例化

```php
require 'vendor/autoload.php';
use Ionepub\Lunar;

$lunar = Lunar::getInstance();
```

### 设置一个阳历日期

```php
$lunar->solar(2018, 9, 10);

# 注意，不能这样传入日期，这样的参数可能会被过滤，如果是两位的日期，应转为字符串
$lunar->solar(2018, 09, 08); // success
$lunar->solar('2018', '09', '08'); // failed
```

### 设置一个阴历日期

```php
$lunar->lunar(2018, 8, 5);
$lunar->lunar('2018', '08', '05');

// lunar方法中第四个参数表示阴历闰月，如果当前阴历日期是闰月的，需要明确传递true
$lunar->lunar(2017, 6, 2, true);
```

### 设置日期为今天

```php 
$lunar->solar();
$lunar->lunar();
```

### 获取日期信息

```php
$result = $lunar->get();
// =>
//    Array
//    (
//	    [year] => 阳历年
//	    [month] => 阳历月
//	    [day] => 阳历日
//	    [lunar_year] => 阴历年
//	    [lunar_month] => 阴历月
//	    [lunar_day] => 阴历日
//	    [lunar_year_chinese] => （中文）阴历年
//	    [lunar_month_chinese] => （中文）阴历月
//	    [lunar_day_chinese] => （中文）阴历日
//	    [is_leap] => 是否闰月
//    )
```

支持链式操作：

```php 
$result = $lunar->solar()->get();
```

### 获取日期字符串

通过向`str()`方法传递第一个参数，可以指定返回的日期类型，分别有：

- Lunar::SOLAR 阳历日期，默认
- Lunar::LUNAR 阴历日期
- Lunar::LUNAR_CN  阴历日期（中文）

```php
$date_str = $lunar->str(); // 2018-09-14
$date_str = $lunar->str(Lunar::SOLAR); // 2018-09-14
$date_str = $lunar->str(Lunar::LUNAR); // 2018-08-05
$date_str = $lunar->str(Lunar::LUNAR_CN); // 二零一八-八月-初五
```

可以通过向`str()`方法传递分隔符参数来设置返回的字符串格式，默认 `-`

```php 
$date_str = $lunar->str(Lunar::SOLAR); // 2018-09-14
$date_str = $lunar->str(Lunar::SOLAR, ' '); // 2018 09 14
$date_str = $lunar->str(Lunar::SOLAR, '.'); // 2018.09.14
```

同样支持链式操作：

```php
$date_str = $lunar->lunar()->str();
```

## 示例

### #已知用户的生日，获取下一次生日的日期

```php
# 如果为阳历生日
$date = '1993-09-14';
$date_arr = explode("-", $date);
$date_arr[0] = date('Y');
if( date("Y-m-d") > implode("-", $date_arr) ){
    // 今年生日已过，获取下一年
    $date_arr[0]++;
}
$next = $calendar->solar($date_arr[0], $date_arr[1], $date_arr[2])->get();
print_r($next);

# 如果为阴历生日
$date = '1993-09-14';
$date_arr = explode("-", $date);
$date_arr[0] = date('Y');
if( $calendar->lunar()->str() > implode("-", $date_arr) ){
    // 今年生日已过，获取下一年
    $date_arr[0]++;
}
$next = $calendar->lunar($date_arr[0], $date_arr[1], $date_arr[2])->get();
print_r($next);
```





