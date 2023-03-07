import Layout from './Shared/Layout'
import { Head } from '@inertiajs/react'

export default function Welcome({ user }) {
  return (
    <Layout>
      <Head title="Welcome" />
      <h1>Welcome</h1>
      <p>Hello , welcome to your first Inertia app!</p>
    </Layout>
  )
}
