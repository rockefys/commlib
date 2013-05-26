<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// |         lanfengye <zibin_5257@163.com>
// +----------------------------------------------------------------------

class Page {
    
    public $prePage='';
    public $nextPage='';
    // 分页栏每页显示的页数
    public $rollPage = 5;
    // 页数跳转时要带的参数
    public $parameter  ;
    // 分页URL地址
    public $url     =   '';
    // 默认列表每页显示行数
    public $listRows = 20;
    // 起始行数
    public $firstRow    ;
    // 分页总页面数
    public $totalPages  ;
    // 总行数
    protected $totalRows  ;
    // 当前页数
    public $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页显示定制
    protected $config  =    array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'首页','last'=>'尾页','theme'=>"%first% %upPage% %linkPage% %downPage% %end% %ajax%");
    // 默认分页变量名
    protected $varPage;

    /**
     * 架构函数
     * @access public
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     */
    public function __construct($totalRows,$listRows='',$parameter='',$url='',$target,$pagesId) {
        $this->scroll = $scroll;
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        $this->url = $url;
        $this->target = $target;
        $this->pagesId = $pagesId;
        $this->varPage = C('VAR_PAGE') ? C('VAR_PAGE') : 'p';
        if (!empty($listRows)) {
            $this->listRows = intval($listRows);
        }
        $this->totalPages = ceil($this->totalRows/$this->listRows); //总页数
        $this->coolPages = ceil($this->totalPages/$this->rollPage);
        $this->nowPage = !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1;
        if($this->nowPage<1){
            $this->nowPage = 1;
        }elseif(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
        $this->firstRow = $this->listRows*($this->nowPage-1);
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }

    /**
     * 分页显示输出
     * @access public
     */
    public function show() {
        if(0 == $this->totalRows) return '';
        $p              =   $this->varPage;
        $nowCoolPage    =   ceil($this->nowPage/$this->rollPage);

        // 分析分页参数
        if($this->url){
            $depr       =   C('URL_PATHINFO_DEPR');
            $url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
        }else{
            if($this->parameter && is_string($this->parameter)) {
                parse_str($this->parameter,$parameter);
            }elseif(is_array($this->parameter)){
                $parameter      =   $this->parameter;
            }elseif(empty($this->parameter)){
                unset($_GET[C('VAR_URL_PARAMS')]);
                $var =  !empty($_POST)?$_POST:$_GET;
                if(empty($var)) {
                    $parameter  =   array();
                }else{
                    $parameter  =   $var;
                }
            }
            $parameter[$p]  =   '__PAGE__';
            $url            =   U('',$parameter);
        }
        //上下翻页字符串
        $upRow          =   $this->nowPage-1;
        $downRow        =   $this->nowPage+1;
        if ($upRow>0){
            $upPage     =   "<li><a id='up-page' href='".str_replace('__PAGE__',$upRow,$url)."'>".$this->config['prev']."</a></li>";
            $this->prePage= "href='".str_replace('__PAGE__',$upRow,$url)."'";
        }else{
            $upPage     =   "<li class='disabled'><a id='up-page'>".$this->config['prev']."</a></li>";
            $this->prePage='disabled="disabled"';
        }

        if ($downRow <= $this->totalPages){
            $downPage   =   "<li><a id='down-page' href='".str_replace('__PAGE__',$downRow,$url)."'>".$this->config['next']."</a></li>";
            $this->nextPage="href='".str_replace('__PAGE__',$downRow,$url)."'";
        }else{
            $downPage   =    "<li class='disabled'><a id='down-page'>".$this->config['next']."</a></li>";
            $this->nextPage='disabled="disabled"';
        }
        // << < > >>
        if($this->nowPage<($this->rollPage/2+1)){
            $theFirst   =   '';
            $prePage    =   '';
        }else{
            $preRow     =   $this->nowPage-$this->rollPage;
            $prePage    =   "<li><a href='".str_replace('__PAGE__',$preRow,$url)."' >上".$this->rollPage."页</a></li>";
            $theFirst   =   "<li><a href='".str_replace('__PAGE__',1,$url)."' >".$this->config['first']."</a></li>";
            
        }
        //$theEndRow  =   $this->totalPages;
        //$theEnd     =   "<li><a href='".str_replace('__PAGE__',$theEndRow,$url)."' >".$this->config['last']."</a></li>";
        if(($this->nowPage + ceil($this->rollPage / 2) > $this->totalPages) && ($this->nowPage + $this->rollPage > $this->totalPages) ){
        //if($nowCoolPage == $this->coolPages){
            $nextPage   =   '';
            $theEnd     =   '';
        }else{
            $nextRow    =   $this->nowPage+$this->rollPage;
            $theEndRow  =   $this->totalPages;
            $nextPage   =   "<li><a href='".str_replace('__PAGE__',$nextRow,$url)."' >下".$this->rollPage."页</a></li>";
            $theEnd     =   "<li><a href='".str_replace('__PAGE__',$theEndRow,$url)."' >".$this->config['last']."</a></li>";
        }
        
        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page       =   $this->nowPage+$i-($this->nowPage > ceil($this->rollPage/2)? ceil($this->rollPage/2) :$this->nowPage);
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "<li><a href='".str_replace('__PAGE__',$page,$url)."'>".$page."</a></li>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= "<li><a class='cur'>".$page."</a></li>";
                }
            }
        }
        
        if($this->target){
                $ajax = <<<eco
     <script>
         $(function(){
            $('#{$this->pagesId} a,#prev-page,#next-page').click(function(){
                var link=$(this).attr('href');
                if(!link)return false;
                $.ajax({
                    url: link,
                    dataType: "html",
                    type: "POST",
                    cache: false,
                    async:true,
                    success: function(html){
                        $("#{$this->target}").html(html);
                        var href=$('#down-page').attr('href');
                        var next=$('#next-page');
                        if (typeof href !== 'undefined' && href !== false) {
                            next.attr('href',href);
                            next.removeAttr('disabled');
                        }
                        else{
                            $('#next-page').removeAttr('href');
                            $('#next-page').attr('disabled','disabled');
                        }

                        href=$('#up-page').attr('href');
                        var prev=$('#prev-page');
                        if (typeof href !== 'undefined' && href !== false) {
                            prev.attr('href',href);
                            prev.removeAttr('disabled');
                        }
                        else{
                            prev.removeAttr('href');
                            prev.attr('disabled','disabled');
                        }
                        finish();
                    }
                });
                return false;
            });
         });
     </script>
eco;
            }
        $pageStr     =   str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%','%ajax%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd,$ajax),$this->config['theme']);
        return $pageStr;
    }

}