<template>
    <div class="card">
        <div class="card-header">
            <div class="row ">
                <div class="col-md-6">
                    <h2>Meetings</h2> 
                </div>
                <div class="col-md-6 text-right" v-if="permission=='1'">
                    <a href="javascript:void(0)" class="btn btn-danger" v-if="!manage" v-on:click="manageForm()">Manage Meeting</a>
                    <a href="javascript:void(0)" class="btn btn-danger" v-if="manage" v-on:click="manageForm()">Done</a>
                </div>
            </div>
        </div>
        <div class="card-body">
          <p>
            <a href="javascript:void(0)" v-on:click="manageAdd()" class="btn btn-primary" v-if="manage">New Meeting</a>
          </p>
         <div class="alert alert-danger" role="alert" v-if="displayError">
            {{ message }}
          </div>
         <div class="row" v-if="!manage" >
            <div class="form-group col-10" v-if="permission=='1'">
              <input type="text" class="form-control" placeholder="Search meeting room or user" v-model="search">
            </div>
             <div class="form-group col-2" v-if="permission=='1'">
              <a href="javascript:void(0)" v-on:click="fetchMyBookings" v-bind:class="{ 'btn btn-primary': onlyMine, 'btn btn-secondary': !onlyMine }">My Bookings</a>
            </div>
         </div>

          <table class="table table-hover" v-if="action=='view'">
            <thead>
                <tr>
                    <th v-if="manage"></th>
                    <th>
                        <a href="javascript:void(0)" v-on:click="sort('meeting_room_name')">
                          Meeting Room
                        </a>
                        <span v-if="sortField =='meeting_room_name' && sortDirection == 'asc'">
                            <i class="fas fa-angle-up"></i>
                        </span>
                        <span v-if="sortField =='meeting_room_name' && sortDirection == 'desc'">
                          <i class="fas fa-angle-down"></i>
                        </span>
                    </th>
                    <th>
                      <a href="javascript:void(0)" v-on:click="sort('schedule_at')">
                        Date Schedule
                      </a>
                       <span v-if="sortField =='schedule_at' && sortDirection == 'asc'">
                           <i class="fas fa-angle-up"></i>
                       </span>
                        <span v-if="sortField =='schedule_at' && sortDirection == 'desc'">
                         <i class="fas fa-angle-down"></i>
                       </span>
                    </th>
                    <th>Created By</th>
                    <th>Duration</th>
                    <th>Time</th>
                    <th v-if="manage"></th>
                </tr>
            </thead>
            <tbody>
                <tr colspan="7" v-if="bookings.data.length<=0">
                  <td>No record found</td>
                </tr>
                <tr v-for="booking in bookings.data">
                  <td v-if="manage">
                    <a href="javascript:void(0)" v-on:click="editData(booking)">
                       <i class=" fas fa-pencil-alt"></i>
                    </a>
                  </td>
                    <td><b>{{ booking.meeting_room_name }}</b></td>
                    <td>{{ booking.schedule_date }}</td>
                     <td>{{ booking.name }}</td>
                    <td>{{ booking.duration }}</td>
                    <td>{{ booking.schedule_time_display }}</td>
                     <td v-if="manage" class="text-right">
                        <a href="javascript:void(0)" class="text-danger" v-on:click="deleteData(booking.id)">
                          <i class="far fa-trash-alt"></i>
                        </a>
                     </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">
                      <pagination :data="bookings" @pagination-change-page="fetchData"></pagination>
                    </td>
                    <td colspan="2" v-if="manage"></td>
                </tr>
            </tfoot>
          </table>
          <!-- This is for the Form --> 
          <div  v-if="action=='add'||action=='edit'">
            <div class="form-group">
              <label for="exampleInputEmail1">Meeting Room</label>
              <input type="text" class="form-control" placeholder="Meeting Room" v-model="field.meeting_room_name">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Schedule Date</label>
              <input type="date" class="form-control" placeholder="Schedule Date" v-model="field.schedule_at" v-on:change="populateTimes()">
            </div>
              <div class="form-group">
              <label for="exampleInputEmail1">Time</label>
              <select name="" id="" class="form-control" v-model="field.from_to">
                  <option value="">Select Time</option>
                  <option v-for="timing in timings" :disabled="timing.lock ? '' : disabled">{{ timing.time }}</option>
              </select>
            </div>
             <div class="form-group">
               <label for="exampleInputEmail1">Duration</label>
               <select class="form-control" v-model="field.duration">
                  <option value="">Select Duration</option>
                  <option value="30 mins">30 mins</option>
                  <option value="1 hour">1 Hour</option>
                </select>
            </div>
            <button type="button" class="btn btn-primary" v-on:click="insertData()">Submit</button>
            <a href="javascript:void(0)" v-on:click="manageAction('view')">Cancel</a>
          </div>
        </div>
    </div>
</template>


<script>

   export default {
      data() {
        return {
           field: {
            meeting_room_name: "",
            schedule_at: "",
            from_to: "",
            duration: "",
           },
          bookings: {},
          timings: {},
          action: 'view',
          manage: false,
          displayError: false,
          search: "",
          sortField: "meeting_room_name",
          sortDirection: "desc",
          onlyMine: 0,
        }
      },
      computed: {
        permission: function () {
          return PERMISSION;
        }
      },
      mounted() {
        this.fetchData();

      },
      watch: {
        search(val, old) {
          if (val.length >= 1 || old.length >=1) {
            this.fetchData();
          }
        }
      },
      methods: {
        sort: function(field) {
          if (this.sortField == field) {
            this.sortDirection = this.sortDirection == 'asc' ? 'desc' : 'asc';
          }
          else {
            this.sortField = field;
            this.sortDirection = 'asc';
          }
          this.fetchData();
        },  
        fetchData: function(page = 1) {
          var self = this;
          axios.get('/api/bookings?api_token='+ API_TOKEN
              +"&page="+page
              + '&sort_field='+this.sortField
              + '&sort_direction='+this.sortDirection
              + '&s='+this.search
              + '&m='+this.onlyMine
              )
          .then(function (response) {
            self.bookings = response.data.bookings;
          }).catch(function (error) {
              console.log(error);
          });

        },
        fetchMyBookings: function() {
          this.onlyMine = this.onlyMine==0? 1 : 0;
          this.fetchData();
        },
        insertData: function () {
          var self = this;
          var url = "";
          if (self.action=='add') {
              url = '/api/bookings?api_token='+ API_TOKEN;
              axios.post(url, self.field).then(function (response) {
                if (response.data.status) {
                  self.clearFields();
                  self.fetchData();
                  self.manageAction('view');
                }
              }).catch(function (error) {
                  console.log(error);
              });
          } 
          else if (self.action=='edit') {
             url = '/api/bookings/'+self.field.id+'?api_token='+ API_TOKEN;
              axios.put(url, self.field).then(function (response) {
                if (response.data.status) {
                  self.clearFields();
                  self.fetchData();
                  self.manageAction('view');
                }
              }).catch(function (error) {
                  console.log(error);
              });
          }
        },
        editData: function(meeting) {
            this.field.schedule_at = meeting.schedule_date;
            this.field.id = meeting.id;
            this.field.meeting_room_name = meeting.meeting_room_name;

            this.populateTimes();

            this.field.from_to = meeting.schedule_time;
            this.field.duration = meeting.duration;
            this.manageAction('edit');
        },
        deleteData: function(id) {
          var self = this;
          var url = "";
          var txt;
          var r = confirm("Are you sure want to delete this meeting? ");
          if (r == true) {
            url = '/api/bookings/'+id+'?api_token='+ API_TOKEN;
              axios.delete(url, self.field).then(function (response) {
                if (response.data.status) {
                  self.fetchData();
                  self.manageAction('view');
                }
              }).catch(function (error) {
                  console.log(error);
              });
          } 
        },
        populateTimes: function() {
          var self = this;
          axios.post('/api/display/timings?api_token='+ API_TOKEN, {
            schedule_at: self.field.schedule_at 
          }).then(function (response) {
            self.timings = response.data.timings;
          }).catch(function (error) {
              console.log(error);
          });
        },
        searchFilter: function() {
          
        },
        clearFields: function() {
          this.field = {
             meeting_room_name: "",
             schedule_at: "",
             from_to: "",
             duration: "",
          };
        },
        manageForm: function() {
          if (this.manage) {
            this.manage = false;
          }
          else {
            this.manage = true;
          }
        },
        manageAction: function(action) {
          this.action = action;
        },
        manageAdd: function() {
          this.action = 'add';
          this.clearFields();
        }
      }

    }
</script>
