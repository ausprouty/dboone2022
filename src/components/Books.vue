
  <div><template>
<div>
  <h2>Books</h2>
    <button class="button" @click="prototypeAll">
      Select ALL to prototype
    </button>
    <button class="button" @click="publishAll">Select ALL to publish</button>

    <button class="button" @click="prototypeNone">Do NOT prototype ANY</button>
    <button class="button" @click="publishNone">Do NOT publish ANY</button>
  </div>
  <div v-for="(book, id) in $v.books.$each.$iter" :key="id" :book="book">
    <div
      class="app-card -shadow"
      v-bind:class="{ notpublished: !book.publish.$model }"
    >
      <div
        class="float-right"
        style="cursor: pointer"
        @click="deleteBookForm(id)"
      >
        X
      </div>
      <h2>Name and Code</h2>
      <div>
        <BaseInput
          v-model="book.id.$model"
          label="Book Number:"
          type="text"
          placeholder="#"
          class="field"
          :class="{ error: book.id.$error }"
          @blur="book.id.$touch()"
        />
      </div>
      <div>
        <BaseInput
          v-model="book.title.$model"
          label="Title:"
          type="text"
          placeholder="Title"
          class="field"
          :class="{ error: book.title.$error }"
          @blur="book.title.$touch()"
        />
        <template v-if="book.title.$error">
          <p v-if="!book.title.required" class="errorMessage">
            Book Title is required
          </p>
        </template>
      </div>
      <div>
        <BaseSelect
          label="Code:"
          :options="bookcodes"
          v-model="book.code.$model"
          class="field"
          :class="{ error: book.code.$error }"
          @mousedown="book.code.$touch()"
        />
      </div>
      <div>
        <p>
          <a class="black" @click="createBook(book.code.$model)"
            >Create new Code</a
          >
        </p>
      </div>
      <div v-bind:id="book.title.$model" v-bind:class="{ hidden: isHidden }">
        <BaseInput
          label="New Code:"
          v-model="book.code.$model"
          type="text"
          placeholder="code"
          class="field"
        />
        <button class="button" @click="addNewBookTitle(book.title.$model)">
          Save Code
        </button>
      </div>
      <div v-if="images">
        <div>
          <h3>Book Image:</h3>
          <div v-if="book.image.$model">
            <img v-bind:src="book.image.$model.image" class="book" />
            <br />
          </div>
          <v-select :options="images" label="title" v-model="book.image.$model">
            <template slot="option" slot-scope="option">
              <img class="select" :src="option.image" />

              <br />
              {{ option.title }}
            </template>
          </v-select>
        </div>
        <div v-if="image_permission">
          <label>
            Add new Image&nbsp;&nbsp;&nbsp;&nbsp;
            <input
              type="file"
              v-bind:id="book.code.$model"
              ref="image"
              v-on:change="handleImageUpload(book.code.$model)"
            />
          </label>
        </div>
      </div>
      <div>
        <h3>Format and Styling:</h3>
        <BaseSelect
          label="Format:"
          :options="formats"
          v-model="book.format.$model"
          class="field"
          :class="{ error: book.format.$error }"
          @mousedown="book.format.$touch()"
        />
        <template v-if="book.format.$error">
          <p v-if="!book.format.required" class="errorMessage">
            Format is required
          </p>
        </template>
      </div>

      <div>
        <BaseSelect
          label="Book and Chapters Style Sheet:"
          :options="styles"
          v-model="book.style.$model"
          class="field"
        />
        <template v-if="style_error">
          <p class="errorMessage">Only .css files may be uploaded</p>
        </template>

        <label>
          Add new stylesheet&nbsp;&nbsp;&nbsp;&nbsp;
          <input
            type="file"
            v-bind:id="book.title.$model"
            ref="style"
            v-on:change="handleStyleUpload(book.code.$model)"
          />
        </label>
        <div>
          <BaseSelect
            label="Editor Styles Shown:"
            :options="ckEditStyleSets"
            v-model="book.styles_set.$model"
            class="field"
            :class="{ error: book.styles_set.$error }"
            @mousedown="book.styles_set.$touch()"
          />
          <template v-if="book.styles_set.$error">
            <p v-if="!book.styles_set.required" class="errorMessage">
              Editor Styles is required
            </p>
          </template>
        </div>
        <div>
          <BaseSelect
            label="Template:"
            :options="templates"
            v-model="book.template.$model"
            class="field"
            :class="{ error: book.template.$error }"
            @mousedown="book.template.$touch()"
          />
          <label>
            Add new template&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input
              type="file"
              v-bind:id="book.title.$model"
              ref="template"
              v-on:change="handleTemplateUpload(book.code.$model)"
            />
          </label>
          <template v-if="template_error">
            <p class="errorMessage">
              Only .html files may be uploaded as templates
            </p>
          </template>
          <button
            class="button yellow"
            @click="
              createTemplate(
                book.template.$model,
                book.style.$model,
                book.styles_set.$model,
                book.title.$model,
                book.code.$model,
                book.format.$model
              )
            "
          >
            Edit or Create Template
          </button>
          <br />
          <label for="checkbox">
            <h3>Hide on library page?&nbsp;&nbsp;</h3>
          </label>
          <input type="checkbox" id="checkbox" v-model="book.hide.$model" />
          <br />
        </div>

        <BaseInput
          label="Password (to add hidden item to library page):"
          v-model="book.password.$model"
          type="text"
          placeholder="password"
          class="field"
        />
        <label for="checkbox">
          <h3>Prototype?&nbsp;&nbsp;</h3>
        </label>
        <input type="checkbox" id="checkbox" v-model="book.prototype.$model" />
        <br />

        <label for="checkbox">
          <h3>Publish?&nbsp;&nbsp;&nbsp;</h3>
        </label>
        <input type="checkbox" id="checkbox" v-model="book.publish.$model" />
        <br />
        <br />
      </div>
    </div>

    <button class="button" @click="addNewBookForm">New Book</button>
    <div v-if="!$v.$anyError">
      <button class="button red" @click="saveForm">Save Changes</button>
    </div>
    <div v-if="$v.$anyError">
      <button class="button grey">Disabled</button>
      <p v-if="$v.$anyError" class="errorMessage">
        Please fill out the required field(s).
      </p>
    </div>
</div>
</template>
<script>
</script>
