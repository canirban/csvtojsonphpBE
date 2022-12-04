import { ErrorMessage, Field, Form, Formik } from 'formik';
import style from './Form.module.css';
import axios from 'axios';
import { urlConfig } from '../../lib/utils/url';
export default function CSVForm(props) {
  const onSubmitHandler = async (values) => {
    let resp = {};
    let error = {};
    urlConfig.data = values;
    try {
      const response = await axios(urlConfig);
      if (!response.data) {
        error = { message: 'Network error please try again later' };
      } else {
        resp = response?.data?.json;
        error = response?.data?.error;
      }
    } catch (e) {
      error = e;
    }
    props.onSubmitForm(resp, error, values.content);
  };
  return (
    <>
      <Formik
        enableReinitialize
        initialValues={{ content: props.content }}
        validate={(values) => {
          const errors = {};
          if (!values.content) {
            errors.content = 'This is a Required field';
          }
          return errors;
        }}
        //separte func
        onSubmit={onSubmitHandler}
      >
        {() => (
          <Form className={style.container}>
            <label className={style.label} htmlFor="content">
              TSV Content
            </label>
            <Field
              className={`${style.input}`}
              component="textarea"
              rows="30"
              id="content"
              name="content"
              cols="50"
            />
            <ErrorMessage
              className={style.error}
              name="content"
              component="div"
            />
            <button className={style.button} type="submit">
              Submit
            </button>
          </Form>
        )}
      </Formik>
    </>
  );
}
