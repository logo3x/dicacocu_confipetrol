// Router shell — switches pages, scrolls to top on nav.
function App() {
  const [route, setRoute] = React.useState('home');
  const go = (r) => { setRoute(r); document.getElementById('kit-scroll').scrollTo({ top: 0 }); };

  let Page;
  if (route === 'services') Page = window.ServiceDetail;
  else if (route === 'contact') Page = window.Contact;
  else Page = window.Home; // home, about, news, sustainability fall back to landing

  return (
    <div style={{ fontFamily: 'var(--font-body)' }}>
      <window.Nav route={route} go={go} />
      <Page go={go} />
      <window.Footer go={go} />
    </div>
  );
}
window.App = App;
