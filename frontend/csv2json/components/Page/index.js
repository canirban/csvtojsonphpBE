import Head from 'next/head';
import Link from 'next/link';
import style from './Page.module.css';
const Page = ({ title, children }) => {
  return (
    <>
      <Head>
        <title>{title}</title>
        <link rel="icon" href="/favicon.ico" />
        {/* Prevent toaster message animations from causing weird page re-sizing. */}
        <meta name="viewport" content="width=device-width, initial-scale=1" />
      </Head>
      <header className={style.header}>
        <h1>Converter App</h1>
      </header>
      <main className={style.main}>{children}</main>
      <footer className={style.footer}>
        <Link href={'https://www.linkedin.com/in/anirbanc17'}>
          &copy; Anirban
        </Link>
      </footer>
    </>
  );
};

export default Page;
