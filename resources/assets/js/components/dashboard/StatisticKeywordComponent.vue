<template>
  <fragment>
    <div class="table-full-width table-responsive">
        <table class="table">
          <thead class=" text-primary">
            <tr>
                <th>
                  {{ text_keyword }}
                </th>
                <th>
                  {{ text_count_search }}
                </th>
            </tr>
          </thead>
          <tbody>
                <tr  v-for="(data,index) in keyword_data">
                    <td>
                      {{ data.name }}
                    </td>
                    <td>
                      {{ data.value | numeral('0,0') }}
                    </td>
                </tr>                 
          </tbody>
        </table>
    </div>   
  </fragment>
</template>
<script>
    export default {
        mounted() {
          //console.log(this.url_update_status,this.user_id);
          //console.log(this.url_api_keyword_search,this.access_token);
          this.api_data;
        },
        data() {
          return {
                  keyword_data:[]
                 } 
        },
        props: {
          text_keyword:{},
          text_count_search:{},
          url_api_keyword_search:{},
          access_token:{},
        },
        methods: {

        },
        computed:{
          api_data:function(){

            const headers = {
              'Content-Type': 'application/json; charset=utf-8',
              'authorization': this.access_token
            }

            axios.post(this.url_api_keyword_search,{
              "device_token":"thrc_backend"
            },{
                headers:headers
            })
            .then(response=>{
                //console.log(response);
                if(response.status ==200){ 
                   this.keyword_data = response.data.val;
                }
                //console.log(this.keyword_data);
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
