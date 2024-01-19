<template>
  <fragment>
    <div class="container" ref="homeCont" style="position: relative;">
        <div class="row">
           <div class="col-12">
                <div class="head_ncdsupdate">
                    <h1>{{ text_title }}</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 tab_ncdsupdate">
                <div class="active">{{ text_situation_ncds_1 }}</div>
                <div>{{ text_situation_ncds_2 }}</div>
            </div>
        </div>
        <div >
            <div class="wrap_tab_ncdsupdate">
                <div class="row row_ncdsupdate">

                    <div class="col-6 col-md-3 item_ncdsupdate_inside" v-for="(data,index) of situation_ncds_1">
                        <a v-bind:href="data.url">
                            <figure>
                                <div><img v-bind:src="data.img" v-bind:alt="data.title"></div>
                                <figcaption>
                                    <h3 class="dotmaster">{{ data.title }}</h3>
                                    <p class="dotmaster">{{ data.description }}</p>
                                    <div class="ncds_dateview">{{ data.created_at }} <img v-bind:src="icon_ncds_eye"> {{ data.hit }}</div>
                                </figcaption>
                            </figure>
                        </a>
                    </div>

                </div>
                <div class="box-viewmoreloandig">
                    <a v-if="situation_ncds_1_lastPage >1" v-on:click="read_more('tab-1')">{{ text_read_more }}</a>
                </div>
            </div>
            <div class="wrap_tab_ncdsupdate">
                <div class="row row_ncdsupdate">

                  <div class="col-6 col-md-3 item_ncdsupdate_inside" v-for="(data,index) of situation_ncds_2">
                        <a v-bind:href="data.url">
                            <figure>
                                <div><img v-bind:src="data.img" v-bind:alt="data.title"></div>
                                <figcaption>
                                    <h3 class="dotmaster">{{ data.title }}</h3>
                                    <p class="dotmaster">{{ data.description }}</p>
                                    <div class="ncds_dateview">{{ data.created_at }} <img v-bind:src="icon_ncds_eye"> {{ data.hit }}</div>
                                </figcaption>
                            </figure>
                        </a>
                    </div>


                </div>
                <div class="box-viewmoreloandig">
                    <a href="#" v-if="situation_ncds_2_lastPage >1"  v-on:click="read_more('tab-2')">{{ text_read_more }}</a>
                </div>
            </div>
        </div>
    </div>
  </fragment>
</template>
<script>
    export default {
        mounted() {
          //console.log(this.api_url,this.access_token);
          this.api_data;
        },
        async created (){
            //await this.api_data;
            //await this.dotmaster
        },
        data() {
          return {
                  situation_ncds_1:[],
                  situation_ncds_1_currentPage:0,
                  situation_ncds_1_lastPage:0,
                  situation_ncds_1_perPage:0,
                  situation_ncds_1_total:0,
                  situation_ncds_2:[],
                  situation_ncds_2_currentPage:0,
                  situation_ncds_2_lastPage:0,
                  situation_ncds_2_perPage:0,
                  situation_ncds_2_total:0,
                  fullPage:false
                 } 
        },
        props: {
          api_url:{},
          api_url_read_more:{},
          access_token:{},
          text_title:{},
          text_situation_ncds_1:{},
          text_situation_ncds_2:{},
          icon_ncds_eye:{},
          text_read_more:{},
          keyword:{},
          issue:{},
          template:{},
          target:{},
          setting:{},
        },
        methods: {
          read_more(key){
              //console.log(key);
              let situation_type;
              let total;
              let lastPage;
              let perPage;
              let nextPage;

              let loader = this.$loading.show({
              container: this.fullPage ? null : this.$refs.homeCont,
              canCancel: false,
              loader:'dots',
              color:'#ef7e25'
              });

              if(key =='tab-1'){

                total = this.situation_ncds_1_total;
                lastPage = this.situation_ncds_1_lastPage;
                perPage =  this.situation_ncds_1_perPage;
                this.situation_ncds_1_currentPage = this.situation_ncds_1_currentPage+1;
                nextPage = this.situation_ncds_1_currentPage;
                situation_type = 1;
              }else{

                total = this.situation_ncds_2_total;
                lastPage = this.situation_ncds_2_lastPage;
                perPage =  this.situation_ncds_2_perPage;
                this.situation_ncds_2_currentPage = this.situation_ncds_2_currentPage+1;
                nextPage = this.situation_ncds_2_currentPage;             
                situation_type = 2;
              }

              if(nextPage >= lastPage){
                if(key =='tab-1'){
                    this.situation_ncds_1_lastPage =1;
                }else{
                    this.situation_ncds_2_lastPage =1;
                }  
              }


                var postData = {
                    keyword:this.keyword,
                    issue:this.issue,
                    template:this.template,
                    target:this.target,
                    setting:this.setting,
                    page:nextPage,
                    situation_type:situation_type
                    //test:window.btoa('2008,2009')
                };
              

              if(nextPage <=lastPage){

                //console.log("True");

                  let axiosConfig = {
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8',
                        'authorization': 'Bearer '+this.access_token
                        //"x-xsrf-token":$('meta[name="csrf-token"]').attr('content'),
                    }
                  };


                  axios.post(this.api_url_read_more,postData,axiosConfig)
                    .then(response=>{
                            // handle success
                        if(response.status ==200){
                        loader.hide();      
                        //console.log(response.data);
                        for(let key_data in response.data.val.situation_ncds){
                            //console.log(response.data.val.situation_ncds[key_data],situation_type);
                            if(situation_type ==1){
                                this.situation_ncds_1.push(response.data.val.situation_ncds[key_data]); 
                            }else{
                                this.situation_ncds_2.push(response.data.val.situation_ncds[key_data]); 
                            }
                            //this.article.push(response.data.tab[key_data]); 
                        }
                        
                        setTimeout(function(){  
                            $('.dotmaster').dotdotdot({
                                watch: 'window'
                            });

                            $( '.tab_ncdsupdate > div' ).click(function (event) {
                                var notab = $(this).index();
                            if (  $( ".wrap_tab_ncdsupdate" ).eq(notab).is( ":hidden" ) ) {
                                    $('.tab_ncdsupdate > div').removeClass("active");
                                    $(this).addClass("active");
                                    $( ".wrap_tab_ncdsupdate" ).hide();
                                    $( ".wrap_tab_ncdsupdate" ).eq(notab).fadeIn();
                            } else {
                                
                            }
                            event.stopPropagation();
                            });

                            var boxfilter01 = $(".tab_ncdsupdate");
                            var  ofstop01 = $('.wrap_tab_ncdsupdate').offset().top;
                            if( $(this).scrollTop() > ofstop01 ) {
                                boxfilter01.addClass('sticky');
                            } else {
                                boxfilter01.removeClass('sticky');
                            }
                            boxfilter01.css({
                                'width': $('.container').outerWidth(),
                                'top': $('.mainnavbar').outerHeight()
                            });

                            $(window).scroll(function() {
                                var boxfilter01 = $(".tab_ncdsupdate");
                                var  ofstop01 = $('.wrap_tab_ncdsupdate').offset().top - 150;
                                if( $(this).scrollTop() > ofstop01 ) {
                                    boxfilter01.addClass('sticky');
                                } else {
                                    boxfilter01.removeClass('sticky');
                                }
                            });                            

                        }, 2000);

                        }
                      })

                    .catch(function (error){
                      // handle error
                      console.log(error);
                    })
                    .then(function(){
                      // always executed
                  });

              }
              //loader.hide();
              //console.log(tag_name,total,lastPage,perPage,nextPage);
          }
        },
        computed:{
          api_data:function(){
            let loader = this.$loading.show({
              container: this.fullPage ? null : this.$refs.homeCont,
              canCancel: false,
              loader:'dots',
              color:'#ef7e25'
            });
            //console.log(this.fullPage,this.$refs.homeCont);
            const headers = {
              'Content-Type': 'application/json; charset=utf-8',
              'authorization': 'Bearer '+this.access_token
            }

            axios.post(this.api_url,{
                keyword:this.keyword,
                issue:this.issue,
                template:this.template,
                target:this.target,
                setting:this.setting,
            },{
                headers:headers
            })
            .then(response=>{
                //console.log(response.data);
                if(response.status ==200){ 
                  loader.hide();
                  this.situation_ncds_1 = response.data.val.situation_ncds_1;
                  this.situation_ncds_1_currentPage = response.data.val.situation_ncds_1_currentPage;
                  this.situation_ncds_1_lastPage = response.data.val.situation_ncds_1_lastPage;
                  this.situation_ncds_1_perPage = response.data.val.situation_ncds_1_perPage;
                  this.situation_ncds_1_total = response.data.val.situation_ncds_1_total;
                  this.situation_ncds_2 = response.data.val.situation_ncds_2;
                  this.situation_ncds_2_currentPage = response.data.val.situation_ncds_2_currentPage;
                  this.situation_ncds_2_lastPage = response.data.val.situation_ncds_2_lastPage;
                  this.situation_ncds_2_perPage = response.data.val.situation_ncds_2_perPage;
                  this.situation_ncds_2_total = response.data.val.situation_ncds_2_total;

                  setTimeout(function(){  
                    $('.dotmaster').dotdotdot({
                        watch: 'window'
                    });

                    $( '.tab_ncdsupdate > div' ).click(function (event) {
                        var notab = $(this).index();
                    if (  $( ".wrap_tab_ncdsupdate" ).eq(notab).is( ":hidden" ) ) {
                            $('.tab_ncdsupdate > div').removeClass("active");
                            $(this).addClass("active");
                            $( ".wrap_tab_ncdsupdate" ).hide();
                            $( ".wrap_tab_ncdsupdate" ).eq(notab).fadeIn();
                    } else {
                        
                    }
                    event.stopPropagation();
                    });


                    var boxfilter01 = $(".tab_ncdsupdate");
                    var  ofstop01 = $('.wrap_tab_ncdsupdate').offset().top;
                    if( $(this).scrollTop() > ofstop01 ) {
                        boxfilter01.addClass('sticky');
                    } else {
                        boxfilter01.removeClass('sticky');
                    }
                    boxfilter01.css({
                        'width': $('.container').outerWidth(),
                        'top': $('.mainnavbar').outerHeight()
                    });

                    $(window).scroll(function() {
                        var boxfilter01 = $(".tab_ncdsupdate");
                        var  ofstop01 = $('.wrap_tab_ncdsupdate').offset().top - 150;
                        if( $(this).scrollTop() > ofstop01 ) {
                            boxfilter01.addClass('sticky');
                        } else {
                            boxfilter01.removeClass('sticky');
                        }
                    });


                  }, 2000);
                }
                //console.log(this.most_viewed_statistic_data);
            })
            .catch(function (error) {
              // handle error
              console.log(error);
            })
            .then(function () {
              // always executed
            });      

          },
          dotmaster:function(){
            console.log("Dot Master");
            $('.dotmaster').dotdotdot({
                watch: 'window'
            });
          }
        }
    }

</script>
