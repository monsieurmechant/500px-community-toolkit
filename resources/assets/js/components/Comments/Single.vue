<template>
  <article :class="[comment.posting_reply ? 'is-disabled':'', 'media', 'comment']">
    <figure class="media-left">
      <p class="image is-64x64">
        <img :src="comment.follower.data.avatar" v-if="!photoThumbnail">
        <img :src="comment.photo.data.thumbnail" v-if="photoThumbnail">
      </p>
    </figure>
    <div class="media-content">
      <div class="content">
        <p>
          <strong>{{ comment.follower.data.name }}</strong>
          <small>
            <a :href="`http://500px.com/${comment.follower.data.username}`">
              <span class="icon is-small"><i class="fa fa-500px"></i></span> {{ comment.follower.data.username }}
            </a>
          </small>
          <small>{{ timeFromUser(comment.created_at) }}</small>
          <br>
          {{ comment.body }}
        </p>
      </div>
      <article class="media" v-for="child in comment.children.data">
        <figure class="media-left">
          <p class="image is-64x64">
            <img :src="child.follower.data.avatar">
          </p>
        </figure>
        <div class="media-content">
          <div class="content">
            <p>
              <strong>{{ child.follower.data.name }}</strong>
              <small>
                <a :href="`http://500px.com/${child.follower.data.username}`">
                  <span class="icon is-small"><i class="fa fa-500px"></i></span> {{ child.follower.data.username }}
                </a>
              </small>
              <small>{{ timeFromUser(child.created_at) }}</small>
              <br>
              {{ child.body }}
            </p>
          </div>
        </div>
        <div class="media-right" v-if="interactions">
          <a class="button is-primary" @click="$emit('markRead', child.id)" v-if="!child.read">
              <span class="icon is-small">
                <i class="fa fa-eye"></i>
              </span>
          </a>
        </div>
      </article>
      <nav class="level" v-if="interactions">
        <div class="level-left">
          <a class="level-item" @click="$emit('requestHistory', comment.follower.data.id)">
            <span class="icon is-small"><i class="fa fa-history"></i></span>
          </a>
          <a class="level-item" @click="replying = !replying">
            <span class="icon is-small"><i class="fa fa-reply"></i></span>
          </a>
        </div>
      </nav>
      <article class="media" v-if="replying">
        <div class="media-content">
          <p class="control">
            <textarea class="textarea" placeholder="Add a comment..." v-model="reply"></textarea>
          </p>
          <p class="control">
            <button class="button" @click="postReply">Reply</button>
          </p>
        </div>
      </article>

    </div>
    <div class="media-right" v-if="interactions">
      <a class="button is-primary" @click="$emit('markRead', comment.id)" v-if="!comment.read">
              <span class="icon is-small">
                <i class="fa fa-eye"></i>
              </span>
      </a>
    </div>
  </article>

</template>
<style>

</style>
<script>
  const moment = require('moment-timezone');
  export default{
    name: 'Comment',
    data() {
      return {
        replying: false,
        reply: null,
      }
    },
    methods: {
      timeFromUser(dateTime) {
        return moment.tz(dateTime.date, dateTime.timezone).
        tz(moment.tz.guess()).fromNow();
      },
      postReply() {
        this.replying = false;
        this.$emit('reply', { body: this.reply, parent_id: this.comment.id })
        this.reply = null;
      }
    },
    props: {
      comment: {
        type: Object,
        required: true,
      },
      interactions: {
        type: Boolean,
        default: true,
      },
      'photo-thumbnail': {
        type: Boolean,
        default: false,
      }
    }
  };



</script>
