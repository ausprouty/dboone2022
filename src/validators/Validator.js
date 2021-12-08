import { helpers } from 'vuelidate/lib/validators'

export const validFilename = helpers.regex('filename', /^[a-zA-Z0-9-_.]*$/)
export const validFoldername = helpers.regex('foldername', /^[a-zA-Z0-9-_/]*$/)
