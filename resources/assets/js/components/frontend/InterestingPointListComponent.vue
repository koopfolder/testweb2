<template>
  <fragment>  
    <div ref="homeCont">
      <section class="row wow fadeInDown">
          <div class="col-xs-12 banner-inside">
              <img v-bind:src="points_to_watch_cover_image">
          </div>
      </section>
      <section class="row wow fadeInDown">
          <div class="container">
              <div class="row row-flex">
                  <div class="col-xs-12 col-md-2 wrap_bg_issue bg_article">
                  
                      <img v-bind:src="icon_article" v-bind:alt="text_article">
                      <div>
                          <h1>{{ text_article }}</h1>
                          <p>
                          {{ points_to_watch_article_description }}
                          </p>
                      </div>
                  </div>
                  <div class="col-xs-12 col-md-10 wrap_issue">
                      <div class="row row_issue">
                          <div class="col-12 col-sm-4 item_issue" v-for="(data,index) of points_to_watch_article">
                              <a v-bind:href="data.url" class="item_related">
                                  <figure>
                                      <div><img v-bind:src="data.img" v-bind:alt="data.title"></div>
                                      <figcaption>
                                          <h5>{{ data.title }}</h5>
                                          <p>{{ data.description }}</p>
                                          <div class="dateandview">{{ data.created_at }}<i class="fas fa-eye"></i>{{ data.hit }}</div>
                                      </figcaption>
                                  </figure>
                              </a>
                          </div>
                      </div>
                  </div>
                  <div class="col-xs-12">
                      <button class="btn_loadmore bg_article" v-if="points_to_watch_article_lastPage >1" v-on:click="redirect('tab-1')">{{ text_read_more }}</button>
                  </div>
              </div>
          </div>
      </section>
      <section class="row bg-issuegrey wow fadeInDown">
          <div class="container">
              <div class="row row-flex">
                  <div class="col-xs-12 col-md-2 wrap_bg_issue bg_video">
                      <img v-bind:src="icon_video" v-bind:alt="text_video">
                      <div>
                          <h1>{{ text_video }}</h1>
                          <p>
                          {{ points_to_watch_video_description }}
                          </p>
                      </div>
                  </div>
                  <div class="col-xs-12 col-md-10 wrap_issue">
                      <div class="row row_issue">
                          <div class="col-12 col-sm-4 item_issue" v-for="(data,index) of points_to_watch_video">
                              <a v-bind:href="data.url" class="item_related item_vdo">
                                  <figure>
                                      <div><img v-bind:src="data.img" v-bind:alt="data.title"></div>
                                      <figcaption>
                                          <h5>{{ data.title }}</h5>
                                          <p>{{ data.description }}</p>
                                          <div class="dateandview">{{ data.created_at }}<i class="fas fa-eye"></i>{{ data.hit }}</div>
                                      </figcaption>
                                  </figure>
                              </a>
                          </div>
                      </div>
                  </div>
                  <div class="col-xs-12">
                      <button class="btn_loadmore bg_video" v-if="points_to_watch_video_lastPage >1" v-on:click="redirect('tab-2')">{{ text_read_more }}</button>
                  </div>
              </div>
          </div>
      </section>
      <section class="row wow fadeInDown">
          <div class="container">
              <div class="row row-flex">
                  <div class="col-xs-12 col-md-2 wrap_bg_issue bg_gallery">
                      <img v-bind:src="icon_gallery" v-bind:alt="text_gallery">
                      <div>
                          <h1>{{ text_gallery }}</h1>
                          <p>
                          {{ points_to_watch_gallery_description }}
                          </p>
                      </div>
                  </div>
                  <div class="col-xs-12 col-md-10 wrap_issue">
                      <div class="row row_issue">
                          <div class="col-12 col-sm-4 item_issue" v-for="(data,index) of points_to_watch_gallery">
                              <a v-bind:href="data.url" class="item_related">
                                  <figure>
                                      <div><img v-bind:src="data.img" v-bind:alt="data.title"></div>
                                      <figcaption>
                                          <h5>{{ data.title }}</h5>
                                          <p>{{ data.description }}</p>
                                          <div class="dateandview">{{ data.created_at }}<i class="fas fa-eye"></i>{{ data.hit }}</div>
                                      </figcaption>
                                  </figure>
                              </a>
                          </div>
                      </div>
                  </div>
                  <div class="col-xs-12">
                      <button class="btn_loadmore bg_gallery"  v-if="points_to_watch_gallery_lastPage >1" v-on:click="redirect('tab-3')">{{ text_read_more }}</button>
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
                  points_to_watch_article_description:'',
                  points_to_watch_video_description:'',
                  points_to_watch_gallery_description:'',
                  points_to_watch_cover_image:'',
                  points_to_watch_article:[],
                  points_to_watch_article_currentPage:0,
                  points_to_watch_article_lastPage:0,
                  points_to_watch_article_perPage:0,
                  points_to_watch_article_total:0,
                  points_to_watch_video:[],
                  points_to_watch_video_currentPage:0,
                  points_to_watch_video_lastPage:0,
                  points_to_watch_video_perPage:0,
                  points_to_watch_video_total:0,
                  points_to_watch_gallery:[],
                  points_to_watch_gallery_currentPage:0,
                  points_to_watch_gallery_lastPage:0,
                  points_to_watch_gallery_perPage:0,
                  points_to_watch_gallery_total:0,
                  fullPage:true
                 } 
        },
        props: {
          text_article:{},
          text_video:{},
          text_gallery:{},
          text_read_more:{},
          api_url:{},
          api_url_read_more:{},
          access_token:{},
          text_read_more:{},
          icon_article:{},
          icon_video:{},
          icon_gallery:{},
          url_article:{},
          url_video:{},
          url_gallery:{}
        },
        methods: {
          read_more(key){
              //console.log(key);
              let type;
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
                total = this.points_to_watch_article_total;
                lastPage = this.points_to_watch_article_lastPage;
                perPage =  this.points_to_watch_article_perPage;
                this.points_to_watch_article_currentPage = this.points_to_watch_article_currentPage+1;
                nextPage = this.points_to_watch_article_currentPage;
                type = 1;
              }else if(key =='tab-2'){

                total = this.points_to_watch_video_total;
                lastPage = this.points_to_watch_video_lastPage;
                perPage =  this.points_to_watch_video_perPage;
                this.points_to_watch_video_currentPage = this.points_to_watch_video_currentPage+1;
                nextPage = this.points_to_watch_video_currentPage;
                type = 2;

              }else{

                total = this.points_to_watch_gallery_total;
                lastPage = this.points_to_watch_gallery_lastPage;
                perPage =  this.points_to_watch_gallery_perPage;
                this.points_to_watch_gallery_currentPage = this.points_to_watch_gallery_currentPage+1;
                nextPage = this.points_to_watch_gallery_currentPage;             
                type = 3;
              }

              if(nextPage >= lastPage){
                if(key =='tab-1'){
                    this.points_to_watch_article_lastPage =1;
                }else if (key =='tab-2'){
                    this.points_to_watch_video_lastPage =1;
                }else{
                    this.points_to_watch_gallery_lastPage =1;
                }  
              }


                var postData = {
                    page:nextPage,
                    type:type
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

                        for(let key_data in response.data.val.points_to_watch){
                            //console.log(response.data.val.situation_ncds[key_data],situation_type);
                            if(type ==1){
                                this.points_to_watch_article.push(response.data.val.points_to_watch[key_data]); 
                            }else if(type ==2){
                                this.points_to_watch_video.push(response.data.val.points_to_watch[key_data]); 
                            }else{
                                this.points_to_watch_gallery.push(response.data.val.points_to_watch[key_data]); 
                            }
                            //this.article.push(response.data.tab[key_data]); 
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
          },
          redirect(key){
              //console.log(key);
              if(key =='tab-1'){
                  window.location.href= this.url_article;
              }else if(key =='tab-2'){
                  window.location.href= this.url_video;
              }else{
                  window.location.href= this.url_gallery;
              }
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

                  this.points_to_watch_article_description= response.data.val.points_to_watch_article_description;
                  this.points_to_watch_video_description= response.data.val.points_to_watch_video_description;
                  this.points_to_watch_gallery_description= response.data.val.points_to_watch_gallery_description;
                  this.points_to_watch_cover_image= response.data.val.points_to_watch_cover_image;
                  this.points_to_watch_article = response.data.val.points_to_watch_article;
                  this.points_to_watch_article_currentPage = response.data.val.points_to_watch_article_currentPage;
                  this.points_to_watch_article_lastPage = response.data.val.points_to_watch_article_lastPage;
                  this.points_to_watch_article_perPage = response.data.val.points_to_watch_article_perPage;
                  this.points_to_watch_article_total = response.data.val.points_to_watch_article_total;
                  this.points_to_watch_video = response.data.val.points_to_watch_video;
                  this.points_to_watch_video_currentPage = response.data.val.points_to_watch_video_currentPage;
                  this.points_to_watch_video_lastPage = response.data.val.points_to_watch_video_lastPage;
                  this.points_to_watch_video_perPage = response.data.val.points_to_watch_video_perPage;
                  this.points_to_watch_video_total = response.data.val.points_to_watch_video_total;       
                  this.points_to_watch_gallery = response.data.val.points_to_watch_gallery;
                  this.points_to_watch_gallery_currentPage = response.data.val.points_to_watch_gallery_currentPage;
                  this.points_to_watch_gallery_lastPage = response.data.val.points_to_watch_gallery_lastPage;
                  this.points_to_watch_gallery_perPage = response.data.val.points_to_watch_gallery_perPage;
                  this.points_to_watch_gallery_total = response.data.val.points_to_watch_gallery_total; 

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
