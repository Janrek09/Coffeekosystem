import Ionicons from "@expo/vector-icons/Ionicons";
import axios from "axios";
import React, { useEffect, useState } from "react";
import {
  ActivityIndicator,
  Dimensions,
  Image,
  ScrollView,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from "react-native";
import { Calendar } from "react-native-calendars";
import { LineChart, PieChart } from "react-native-chart-kit";
import { Appbar, Card } from "react-native-paper";

export default function Dashboard({ navigation }: any) {
  const screenWidth = Dimensions.get("window").width - 32;
  const [selected, setSelected] = useState("");

  // API states
  const [summary, setSummary] = useState<any>(null);
  const [weeklySales, setWeeklySales] = useState<any>(null);
  const [topProducts, setTopProducts] = useState<any[]>([]);
  const [events, setEvents] = useState<any>({});
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const [summaryRes, salesRes, productsRes, eventsRes] = await Promise.all([
          axios.get("http://10.0.2.2:8000/api/summary"),
          axios.get("http://10.0.2.2:8000/api/sales/weekly"),
          axios.get("http://10.0.2.2:8000/api/products/bestsellers"),
          axios.get("http://10.0.2.2:8000/api/events"),
        ]);

        setSummary(summaryRes.data);
        setWeeklySales({
          labels: salesRes.data.labels,
          datasets: [{ data: salesRes.data.data }],
        });
        setTopProducts(
          productsRes.data.map((p: any) => ({
            name: p.name,
            sales: p.sales,
            color: p.color,
            legendFontColor: "#333",
            legendFontSize: 12,
          }))
        );
        setEvents(eventsRes.data);
      } catch (err) {
        console.error("API Error:", err);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  const todaysEvents = events[selected] || [];

  // 🔐 Prevent rendering until data is ready
  if (loading || !summary) {
    return (
      <View style={{ flex: 1, justifyContent: "center", alignItems: "center" }}>
        <ActivityIndicator size="large" color="#6D4C41" />
        <Text style={{ marginTop: 8 }}>Loading Dashboard...</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      {/* Header */}
      <Appbar.Header style={styles.header}>
        <Appbar.Content title="Coffee-Ko Dashboard" color="#fff" />
      </Appbar.Header>

      <ScrollView>
        {/* Summary Cards */}
        <View style={styles.cardGrid}>
          <Card style={styles.card}>
            <Card.Content style={styles.cardContent}>
              <Image source={{ uri: "https://cdn-icons-png.flaticon.com/512/924/924514.png" }} style={styles.icon} />
              <View>
                <Text style={styles.cardTitle}>Inventory</Text>
                <Text style={styles.cardValue}>
                  {summary?.inventory != null ? `${summary.inventory} items` : "N/A"}
                </Text>
              </View>
            </Card.Content>
          </Card>

          <Card style={styles.card}>
            <Card.Content style={styles.cardContent}>
              <Image source={{ uri: "https://cdn-icons-png.flaticon.com/512/590/590836.png" }} style={styles.icon} />
              <View>
                <Text style={styles.cardTitle}>Sales Today</Text>
                <Text style={styles.cardValue}>
                  ₱{summary?.salesToday != null ? summary.salesToday : "N/A"}
                </Text>
              </View>
            </Card.Content>
          </Card>

          <Card style={styles.card}>
            <Card.Content style={styles.cardContent}>
              <Image source={{ uri: "https://cdn-icons-png.flaticon.com/512/3081/3081559.png" }} style={styles.icon} />
              <View>
                <Text style={styles.cardTitle}>Products</Text>
                <Text style={styles.cardValue}>
                  {summary?.products != null ? `${summary.products} total` : "N/A"}
                </Text>
              </View>
            </Card.Content>
          </Card>

          <Card style={styles.card}>
            <Card.Content style={styles.cardContent}>
              <Image source={{ uri: "https://cdn-icons-png.flaticon.com/512/2927/2927347.png" }} style={styles.icon} />
              <View>
                <Text style={styles.cardTitle}>Deliveries</Text>
                <Text style={styles.cardValue}>
                  {summary?.deliveries != null ? `${summary.deliveries} pending` : "N/A"}
                </Text>
              </View>
            </Card.Content>
          </Card>
        </View>

        {/* Weekly Sales Chart */}
        <Card style={styles.chartCard}>
          <Card.Content>
            <Text style={styles.chartTitle}>Weekly Revenue</Text>
            {weeklySales && (
              <LineChart
                data={weeklySales}
                width={screenWidth}
                height={220}
                chartConfig={{
                  backgroundGradientFrom: "#6D4C41",
                  backgroundGradientTo: "#8D6E63",
                  color: (opacity = 1) => `rgba(255,255,255,${opacity})`,
                  labelColor: () => "#fff",
                }}
                bezier
                style={{ borderRadius: 12 }}
              />
            )}
          </Card.Content>
        </Card>

        {/* Best Sellers Pie Chart */}
        <Card style={styles.chartCard}>
          <Card.Content>
            <Text style={styles.chartTitle}>Best Sellers</Text>
            {topProducts.length > 0 && (
              <PieChart
                data={topProducts}
                width={screenWidth}
                height={220}
                accessor={"sales"}
                backgroundColor={"transparent"}
                paddingLeft={"15"}
                chartConfig={{ color: () => "#333" }}
                absolute
              />
            )}
          </Card.Content>
        </Card>

        {/* Calendar */}
        <Card style={styles.chartCard}>
          <Card.Content>
            <Text style={styles.chartTitle}>Calendar</Text>
            <Calendar
              onDayPress={(day) => setSelected(day.dateString)}
              markedDates={{
                [selected]: { selected: true, selectedColor: "#6D4C41", selectedTextColor: "#fff" },
                ...Object.keys(events).reduce((acc: any, date: string) => {
                  acc[date] = { marked: true, dotColor: "#F59E0B" };
                  return acc;
                }, {}),
              }}
              theme={{
                selectedDayBackgroundColor: "#6D4C41",
                todayTextColor: "#F59E0B",
                arrowColor: "#6D4C41",
              }}
            />

            {/* Events List */}
            <View style={styles.eventsContainer}>
              <Text style={styles.chartTitle}>
                {selected ? `Events on ${selected}` : "Select a date"}
              </Text>
              {todaysEvents.length > 0 ? (
                todaysEvents.map((event: any, index: number) => (
                  <View key={index} style={styles.eventItem}>
                    <Ionicons name="calendar" size={20} color="#6D4C41" />
                    <Text style={styles.eventText}>
                      {event.time} - {event.title}
                    </Text>
                  </View>
                ))
              ) : (
                <Text style={styles.noEvent}>No events</Text>
              )}
            </View>
          </Card.Content>
        </Card>

        {/* Quick Menu */}
        <View style={styles.menuSection}>
          <Text style={styles.chartTitle}>Quick Menu</Text>
          <View style={styles.menuGrid}>
            <TouchableOpacity style={styles.menuButton} onPress={() => navigation.navigate("Inventory")}>
              <Ionicons name="cube" size={24} color="#fff" />
              <Text style={styles.menuText}>Inventory</Text>
            </TouchableOpacity>

            <TouchableOpacity style={styles.menuButton} onPress={() => navigation.navigate("Products")}>
              <Ionicons name="cafe" size={24} color="#fff" />
              <Text style={styles.menuText}>Products</Text>
            </TouchableOpacity>

            <TouchableOpacity style={styles.menuButton} onPress={() => navigation.navigate("Orders")}>
              <Ionicons name="cart" size={24} color="#fff" />
              <Text style={styles.menuText}>Orders</Text>
            </TouchableOpacity>

            <TouchableOpacity style={styles.menuButton} onPress={() => navigation.navigate("Deliveries")}>
              <Ionicons name="bicycle" size={24} color="#fff" />
              <Text style={styles.menuText}>Deliveries</Text>
            </TouchableOpacity>
          </View>
        </View>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: "#F5E6DA" },
  header: { backgroundColor: "#6D4C41" },
  cardGrid: { flexDirection: "row", flexWrap: "wrap", justifyContent: "space-between", margin: 12 },
  card: { width: "48%", marginBottom: 12, borderRadius: 12, elevation: 3 },
  cardContent: { flexDirection: "row", alignItems: "center" },
  cardTitle: { fontSize: 14, fontWeight: "600", color: "#333" },
  cardValue: { fontSize: 18, fontWeight: "bold", color: "#6D4C41" },
  icon: { width: 40, height: 40, marginRight: 12 },
  chartCard: { margin: 12, borderRadius: 12, elevation: 3 },
  chartTitle: { fontSize: 16, fontWeight: "bold", marginBottom: 8, color: "#6D4C41" },
  eventsContainer: { marginTop: 12 },
  eventItem: { flexDirection: "row", alignItems: "center", marginBottom: 6 },
  eventText: { marginLeft: 6, color: "#333" },
  noEvent: { fontStyle: "italic", color: "#777" },
  menuSection: { margin: 12, padding: 12 },
  menuGrid: { flexDirection: "row", justifyContent: "space-between", flexWrap: "wrap" },
  menuButton: {
    backgroundColor: "#6D4C41",
    width: "48%",
    padding: 16,
    marginBottom: 12,
    borderRadius: 12,
    alignItems: "center",
    flexDirection: "column",
  },
  menuText: { color: "#fff", marginTop: 6, fontWeight: "600" },
});
