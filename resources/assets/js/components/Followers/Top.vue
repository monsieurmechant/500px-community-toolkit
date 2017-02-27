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
        <tr>
          <td>{{ i + 1 }}</td>
          <td><a :href="`http://500px.com/${follower.username}`">{{ follower.name }}</a></td>
          <td>{{ follower.followers_count }}</td>
          <td>{{ follower.affection }}</td>
        </tr>
        <tr>
          <td colspan="4">
            <div class="card">
              <div class="card-image">
                <figure class="image is-4by3">
                  <img src="http://bulma.io/images/placeholders/1280x960.png" alt="Image">
                </figure>
              </div>
              <div class="card-content">
                <div class="media">
                  <div class="media-left">
                    <figure class="image" style="height: 40px; width: 40px;">
                      <img src="http://bulma.io/images/placeholders/96x96.png" alt="Image">
                    </figure>
                  </div>
                  <div class="media-content">
                    <p class="title is-4">John Smith</p>
                    <p class="subtitle is-6">@johnsmith</p>
                  </div>
                </div>

                <div class="content">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                  Phasellus nec iaculis mauris. <a>@bulmaio</a>.
                  <a>#css</a> <a>#responsive</a>
                  <br>
                  <small>11:09 PM - 1 Jan 2016</small>
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
            followers: [],
            loading: true,
          }
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
