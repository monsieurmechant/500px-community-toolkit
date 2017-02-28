<template>
  <div class="followers-top">
    <div class="tabs is-centered">
      <ul>
        <li class="is-active">
          <a>
            <span class="icon is-small"><i class="fa fa-users"></i></span>
            <span>Top 50 by Followers</span>
          </a>
        </li>
        <li>
          <a>
            <span class="icon is-small"><i class="fa fa-heart"></i></span>
            <span>Top 50 by Affection</span>
          </a>
        </li>
      </ul>
    </div>
    <table class="table">
      <thead>
      <tr>
        <th><abbr title="Position">Pos</abbr></th>
        <th>Username</th>
        <th><abbr title="Followers">Followers</abbr></th>
        <th><abbr title="Affection">Affection</abbr></th>
      </tr>
      </thead>
      <tfoot>
      <tr>
        <th><abbr title="Position">Pos</abbr></th>
        <th>Username</th>
        <th><abbr title="Followers">Followers</abbr></th>
        <th><abbr title="Affection">Affection</abbr></th>
      </tr>
      </tfoot>
      <tbody>
      <template v-for="(follower, i) in followers">
        <tr @click="showProfileDetails(i+1, follower.id)">
          <td>{{ i + 1 }}</td>
          <td><a :href="`http://500px.com/${follower.username}`">{{ follower.name }}</a></td>
          <td>{{ follower.followers_count }}</td>
          <td>{{ follower.affection }}</td>
        </tr>
        <tr v-if="details.row === i+1">
          <td colspan="4">
            <div class="card">
              <div class="card-image" v-if="details.profile.cover_url">
                <figure class="image is-2by1">
                  <img :src="details.profile.cover_url" alt="Image">
                </figure>
              </div>
              <div class="card-content">
                <div class="media">
                  <div class="media-left">
                    <figure class="image" style="height: 40px; width: 40px;">
                      <img :src="details.profile.avatars.small.https" alt="Image">
                    </figure>
                  </div>
                  <div class="media-content">
                    <p class="title is-4">
                      {{ details.profile.fullname }}
                    </p>
                    <p class="subtitle is-6">
                      <a :href="`http://500px.com/${follower.username}`">
                        <span class="icon is-small"><i class="fa fa-500px"></i></span> @{{ details.profile.username }}
                      </a>

                    </p>
                  </div>
                </div>

                <div class="content has-text-centered">
                  <p>{{ details.profile.about }}</p>
                  <br>
                  <span class="tag is-info is-large">
                    <span class="icon is-small"><i class="fa fa-users"></i></span>
                    {{ details.profile.followers_count }}
                  </span>
                  <span class="tag is-info is-large">
                    <span class="icon is-small"><i class="fa fa-heart"></i></span>
                    {{ details.profile.affection }}
                  </span>
                  <span class="tag is-info is-large" v-if="details.profile.following">
                    <span class="icon is-small"><i class="fa fa-user-plus"></i></span>
                    Followed Back
                  </span>
                  <span class="tag is-info is-large" v-if="!details.profile.following">
                    <span class="icon is-small"><i class="fa fa-user-times"></i></span>
                    Not Followed
                  </span>
                </div>
              </div>
            </div>
          </td>
        </tr>
      </template>
      </tbody>
    </table>
  </div>
</template>

<script>
    import Axios from 'axios';
    export default {
        name: 'Followers-Top',
        data:  function () {
          return {
            details: {
              row: 0,
              profile: {},
            },
            followers: [],
            loading: true,
          }
        },
        methods: {
          showProfileDetails(row, id) {
            Axios.get(`/internal/followers/${id}?full=1`).then(response => {
              this.details.profile = response.data.user;
              this.details.row = this.details.row === row ? 0:row;
            }).catch();

          },
        },
        mounted() {
          this.loading = true;
          Axios.get('/internal/followers').then(response => {
            this.followers = response.data.data;
            this.loading = false;
          }).catch();
        }
    }




</script>
