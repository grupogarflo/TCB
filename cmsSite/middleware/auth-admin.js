export default function ({ store, redirect }) {
  const user = store.state.auth.loggedIn
  // if (!user && window.location.pathname !== "/") { /* return redirect('/') */ router.push("/") }
  if (!user) { return redirect('/'); }
}
