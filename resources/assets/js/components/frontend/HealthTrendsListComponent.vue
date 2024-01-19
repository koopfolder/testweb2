<template>
  <fragment>  
    <div ref="homeCont">
    <section class="row wow fadeInDown">
        <div class="col-xs-12 banner-inside" v-show="health_trends_cover_image !=''">
            <img v-bind:src="health_trends_cover_image" v-bind:alt="text_title">
        </div>
    </section>
    <section class="row wow fadeInDown">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 head_inside">
                    <h1>{{ text_title }}</h1>
                    <p>
                      {{ health_trends_description }}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="row wow fadeInDown">
        <div class="container">
            <div class="row row_trend">
                <div class="col-xs-12 col-sm-6 col-md-4 item_trend" v-for="(data,index) of data">
                    <a  v-bind:href="data.url">
                        <figure>
                            <div>
                                <img v-bind:src="data.img" v-bind:alt="data.title">
                            </div>
                            <figcaption>
                                <h2>{{ data.title }}</h2>
                            </figcaption>
                        </figure>
                    </a>
                </div>                
            </div>
            <div class="row">
                <div class="col-xs-12">
                   <button class="btn_loadmore bg_article" v-if="health_trends_lastPage >1" v-on:click="read_more()">{{ text_read_more }}</button>
                </div>
            </div>
        </div>
    </section>        
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
                  health_trends_description:'',
                  health_trends_cover_image:'',
                  data:[],
                  health_trends_currentPage:0,
                  health_trends_lastPage:0,
                  health_trends_perPage:0,
                  health_trends_total:0,
                  fullPage:true
                 } 
        },
        props: {
          text_title:{},
          api_url:{},
          api_url_read_more:{},
          access_token:{},
          text_read_more:{},
        },
        methods: {
          read_more(){
              //console.log(key);
            
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



                total = this.health_trends_total;
                lastPage = this.health_trends_lastPage;
                perPage =  this.health_trends_perPage;
                this.health_trends_currentPage = this.health_trends_currentPage+1;
                nextPage = this.health_trends_currentPage; 
                          
            
                if(nextPage >= lastPage){
                    this.health_trends_lastPage  = 1
                }


                var postData = {
                    page:nextPage,
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
                        for(let key_data in response.data.val.health_trends){
                            //console.log();
                            this.data.push(response.data.val.health_trends[key_data]); 
                        }
                        
                        setTimeout(function(){  

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

            axios.post(this.api_url,{},{
                headers:headers
            })
            .then(response=>{
                //console.log(response.data);
                if(response.status ==200){ 
                  loader.hide();

                  this.data = response.data.val.health_trends;
                  this.health_trends_description = response.data.val.health_trends_description;
                  this.health_trends_cover_image = response.data.val.health_trends_cover_image;
                  this.health_trends_currentPage = response.data.val.health_trends_currentPage;
                  this.health_trends_lastPage = response.data.val.health_trends_lastPage;
                  this.health_trends_perPage = response.data.val.health_trends_perPage;
                  this.health_trends_total = response.data.val.health_trends_total; 
                  //console.log(this.panel_discussion);
                  setTimeout(function(){  
                    
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

          }
        }
    }

</script>
