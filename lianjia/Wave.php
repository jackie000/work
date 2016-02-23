<?php
/**
 * wav音频文件处理类
 * 
 * @filename	wavdb.php 
 * @encoding	UTF-8 
 * @author	肖武 <lh_ts24@qq.com>
 * @datetime	2014-6-22  11:47:38
 * @version	1.0
 */

ini_set("display_errors","Off");
error_reporting( 0 );

class Wave {
    protected $fp = null;
    /**
     * 构造函数
     * @param string $wav_file
     */
    function __construct($wav_file) {
        $this->fp = fopen($wav_file, 'rb');
        if (!$this->fp) {
            exit('打开wav文件失败：'.$wav_file);
        }
    }

    /**
     * 析构函数
     */
    function __destruct() {
        if ($this->fp) {
            fclose($this->fp);
        }
    }

    /**
     * 判断是否是wav格式文件
     * @return bool
     */
    public function is_wav() {
        return $this->get_betys(0x00, 4) == 'RIFF' && $this->get_betys(0x08, 4) == 'WAVE';
    }

    public function get_info() {
        return array(
            'a' => array('格式类别', $this->get_unpack(0x14, 2, 's')),
            'b' => array('通道数', $this->get_unpack(0x16, 2, 's')),
            'c' => array('采样率', $this->get_unpack(0x18, 2, 's')),
            'd' => array('传输速率', $this->get_unpack(0x1c, 4, 'L')),
            'e' => array('数据块大小', $this->get_unpack(0x20, 2, 's')),
            'f' => array('样本位数', $this->get_unpack(0x22, 2, 's')),
            'g' => array('语音数据长度', $this->get_unpack(0x28, 4, 'L')),

        );
    }

    /**
     * 读取二进制数据，并upack
     * @param int $offset		偏移量，字节
     * @param int $len		长度，字节	
     * @param string $format	unpack的format
     * @retur mixed 若unpack后数组只有一个单元，则去掉外层数组
     */
    protected function get_unpack($offset, $len, $format) {
        $bin = $this->get_betys($offset, $len);
        $arr = unpack($format, $bin);
        //print_r($arr);
        return count($arr) == 1 ? $arr[1] : $arr;
    }


    /**
     * 读取指定位置的二进制数据
     * @param int $offset	偏移量，字节
     * @param int $len	长度，字节
     * @return bin
     */
    protected function get_betys($offset, $len) {
        fseek($this->fp, $offset);
        //var_dump(feof($this->fp));
        return fread($this->fp, $len);
    }

    /**
     * 平均分贝值
     */
    public function avg_db() {
        //$data_len = $this->get_unpack(0x28, 4, 'L');
        $sBetys = 36;
        $data = $this->get_unpack(0, $sBetys, 'A4Ri/VSize/A4Wav/A4Head/VHeadSize/vPCM/vChannels/VSampleRate/VByteRate/vBlockAlign/vSampleBits');
        $data_len = ( $data['Size'] / ( $data['SampleBits'] * $data['Channels'] / 8  ) );
        //echo $data_len . "\r\n";
        //var_dump( $data );
        //exit;
        fseek($this->fp, 36);

        $len = 100;
        $loop = ceil($data_len / $len);
        $arr_db = array();
        for ($i=0; $i<$loop; $i++) {
            $bin = fread($this->fp, $len);
            //echo $i,"\n";
            $arr = unpack('s*', $bin);
            //print_r($arr);
            $item = $this->dB($arr);
            //echo $item . "\r\n";
            $arr_db[] = $item;
            //print_r($arr);
        }

        return array_sum($arr_db)/count($arr_db);
    }


    public function dB($source) {
        //	    int sourceLen = source.Length;
        $sourceLen = count($source);
        //            int nu = (int)(Math.Log(sourceLen) / Math.Log(2));
        $nu = (int)(log($sourceLen)/log(2));
        //            int halfSourceLen = sourceLen / 2;
        $halfSourceLen = (int)($sourceLen / 2);
        //            int nu1 = nu - 1;
        $nu1 = $nu -1;
        //            double[] xre = new double[sourceLen];
        $xre = array();
        //            double[] xim = new double[sourceLen];
        $xin = array();
        //            double[] decibel = new double[halfSourceLen];
        $decibel = array();
        //            double tr, ti, p, arg, c, s;
        $tr=0; $ti=0; $p=0; $arg=0; $c=0; $s=0;
        //            for (int i = 0; i < sourceLen; i++)
        //            {
        //                xre[i] = source[i];
        //                xim[i] = 0.0f;
        //            }
        for ($i =0; $i<$sourceLen; $i++) {
            $xre[$i] = $source[$i];
            $xim[$i] = 0.0;
        }
        //            int k = 0;
        $k = 0;
        //            for (int l = 1; l <= nu; l++)
        //            {
        for ($l=1; $l<=$nu; $l++){
            //                while (k < sourceLen)
            //                {
            while($k < $sourceLen){

                //                    for (int i = 1; i <= halfSourceLen; i++)
                //                    {
                for ($i=1;$i<=$halfSourceLen; $i++){

                    //                        p = BitReverse(k >> nu1, nu);
                    //                        arg = 2 * (double)Math.PI * p / sourceLen;
                    //                        c = (double)Math.Cos(arg);
                    //                        s = (double)Math.Sin(arg);
                    $p = $this->BitReverse($k >> $nu1, $nu);
                    $arg = 2 * pi() * $p / $sourceLen;
                    $c = cos($arg);
                    $s = sin($arg);
                    //                        tr = xre[k + halfSourceLen] * c + xim[k + halfSourceLen] * s;
                    //                        ti = xim[k + halfSourceLen] * c - xre[k + halfSourceLen] * s;
                    //                        xre[k + halfSourceLen] = xre[k] - tr;
                    //                        xim[k + halfSourceLen] = xim[k] - ti;
                    //                        xre[k] += tr;
                    //                        xim[k] += ti;
                    //                        k++;
                    $idx = $k+$halfSourceLen;
                    $tr = $xre[$idx] * $c + $xim[$idx] * $s;
                    $ti = $xim[$idx] * $c - $xre[$idx] * $s;
                    $xre[$idx] = $xre[$k] - $tr;
                    $xim[$idx] = $xim[$k] - $ti;
                    $xre[$k] += $tr;
                    $xim[$k] += $ti;
                    $k++;
                }
                //                    }

                //                    k += halfSourceLen;
                $k += $halfSourceLen;
                //                }
            }
            //                k = 0;
            //                nu1--;
            //                halfSourceLen = halfSourceLen / 2;
            $k = 0;
            $nu1--;
            $halfSourceLen = intval($halfSourceLen / 2);
            //            }
        }
        //            k = 0;
        //            int r;
        $k = 0;
        $r = 0;
        //            while (k < sourceLen)
        //            {
        //                r = BitReverse(k, nu);
        //                if (r > k)
        //                {
        //                    tr = xre[k];
        //                    ti = xim[k];
        //                    xre[k] = xre[r];
        //                    xim[k] = xim[r];
        //                    xre[r] = tr;
        //                    xim[r] = ti;
        //                }
        //                k++;
        //            }
        while ($k < $sourceLen) {
            $r = $this->BitReverse($k, $nu);
            if ($r > $k) {
                $tr = $xre[$k];
                $ti = $xim[$k];
                $xre[$k] = $xre[$r];
                $xim[$k] = $xim[$r];
                $xre[$r] = $tr;
                $xim[$r] = $ti;
            }
            $k++;
        }
        //            for (int i = 0; i < sourceLen / 2; i++)
        //            {
        //                decibel[i] = 10.0 * Math.Log10((float)(Math.Sqrt((xre[i] * xre[i]) + (xim[i] * xim[i]))));
        //            }
        //
        //            return decibel;
        $max = (int)($sourceLen/2);
        $sum = 0;
        for ($i=0; $i<$max; $i++) {
            $sum += 10*log(sqrt($xre[$i]*$xre[$i] + $xim[$i]*$xim[$i]), 10);

        }
        $out = ($sum / $max)*2;

        return $out > 0 ? $out : 0;
    }

    /**
     * 
     * @param int $j
     * @param int $nu
     * @return string
     */
    private function BitReverse($j, $nu){
        //int j2;
        //int j1 = j;
        //int k = 0;
        //	    for (int i = 1; i <= nu; i++)
        //	    {
        //		j2 = j1 / 2;
        //		k = 2 * k + j1 - 2 * j2;
        //		j1 = j2;
        //	    }
        //	    return k;

        $j2 = 0;
        $j1 = $j;
        $k = 0;
        for ($i=1; $i<$nu; $i++) {
            $j2 = $j1 / 2;
            $k = 2 * $k + $j1 - 2 * $j2;
            $j1 = $j2;
        }

        return $k;
    }
}

//echo '<xmp>';
//$file = empty($_GET['f']) ? 'D:\IIS_WebSite\htdocs\lianjia\2016-02-23\openid_oBpGisxJQcZoDz31k8pK43QGsJN8\20160223121654.wav' : $_GET['f'];
//$file = "20160223135515.wav";
//$wav = new Wave($file);
////var_dump($wav->is_wav());
////print_r($wav->get_info());
//echo $wav->avg_db();

?>
