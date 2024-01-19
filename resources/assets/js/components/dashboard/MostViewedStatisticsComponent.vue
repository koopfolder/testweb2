<template>
  <fragment>
    <div class="table-full-width table-responsive">
        <table class="table">
          <thead class=" text-primary">
            <tr>
                <th>
                  {{ text_title }}
                </th>
                <th>
                  {{ text_number_of_visitors }}
                </th>
            </tr>
          </thead>
          <tbody>
               <tr  v-for="(data,index) in most_viewed_statistic_data">
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
          //console.log(this.url_api_most_viewed_statistic,this.access_token);
          this.api_data;
        },
        data() {
          return {
                  most_viewed_statistic_data:[]
                 } 
        },
        props: {
          text_title:{},
          text_number_of_visitors:{},
          url_api_most_viewed_statistic:{},
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

            axios.post(this.url_api_most_viewed_statistic,{
              "device_token":"thrc_backend"
            },{
                headers:headers
            })
            .then(response=>{
                //console.log(response);
                if(response.status ==200){ 
                   this.most_viewed_statistic_data = response.data.val;
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
