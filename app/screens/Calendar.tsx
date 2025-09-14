// app/screens/Dashboard.tsx
import React, { useState } from "react";
import { Dimensions, Image, ScrollView, StyleSheet, Text, View } from "react-native";
import { Calendar } from "react-native-calendars";
import { LineChart, PieChart } from "react-native-chart-kit";
import { Appbar, Card } from "react-native-paper";

export default function Dashboard() {
  const screenWidth = Dimensions.get("window").width - 32;

  const [selected, setSelected] = useState("");

  // Sample chart data
  const weeklySales = {
    labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
    datasets: [{ data: [1200, 900, 1500, 800, 1700, 2200, 1900] }],
  };

  const topProducts = [
    { name: "Latte", sales: 40, color: "#6D4C41", legendFontColor: "#333", legendFontSize: 12 },
    { name: "Cappuccino", sales: 25, color: "#A1887F", legendFontColor: "#333", legendFontSize: 12 },
    { name: "Espresso", sales: 20, color: "#F59E0B", legendFontColor: "#333", legendFontSize: 12 },
    { name: "Mocha", sales: 15, color: "#3B82F6", legendFontColor: "#333", legendFontSize: 12 },
  ];

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
                <Text style={styles.cardValue}>120 items</Text>
              </View>
            </Card.Content>
          </Card>

          <Card style={styles.card}>
            <Card.Content style={styles.cardContent}>
              <Image source={{ uri: "https://cdn-icons-png.flaticon.com/512/590/590836.png" }} style={styles.icon} />
              <View>
                <Text style={styles.cardTitle}>Sales Today</Text>
                <Text style={styles.cardValue}>₱4,500</Text>
              </View>
            </Card.Content>
          </Card>

          <Card style={styles.card}>
            <Card.Content style={styles.cardContent}>
              <Image source={{ uri: "https://cdn-icons-png.flaticon.com/512/3081/3081559.png" }} style={styles.icon} />
              <View>
                <Text style={styles.cardTitle}>Products</Text>
                <Text style={styles.cardValue}>45 total</Text>
              </View>
            </Card.Content>
          </Card>

          <Card style={styles.card}>
            <Card.Content style={styles.cardContent}>
              <Image source={{ uri: "https://cdn-icons-png.flaticon.com/512/2927/2927347.png" }} style={styles.icon} />
              <View>
                <Text style={styles.cardTitle}>Deliveries</Text>
                <Text style={styles.cardValue}>5 pending</Text>
              </View>
            </Card.Content>
          </Card>
        </View>

        {/* Weekly Sales Chart */}
        <Card style={styles.chartCard}>
          <Card.Content>
            <Text style={styles.chartTitle}>Weekly Revenue</Text>
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
          </Card.Content>
        </Card>

        {/* Best Sellers Pie Chart */}
        <Card style={styles.chartCard}>
          <Card.Content>
            <Text style={styles.chartTitle}>Best Sellers</Text>
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
          </Card.Content>
        </Card>

        {/* REAL Calendar */}
        <Card style={styles.chartCard}>
          <Card.Content>
            <Text style={styles.chartTitle}>Calendar</Text>
            <Calendar
              onDayPress={(day) => {
                setSelected(day.dateString);
              }}
              markedDates={{
                [selected]: {
                  selected: true,
                  selectedColor: "#6D4C41",
                  selectedTextColor: "#fff",
                },
                "2025-09-05": { marked: true, dotColor: "#F59E0B" }, // sample event
                "2025-09-12": { marked: true, dotColor: "#3B82F6" }, // sample event
              }}
              theme={{
                selectedDayBackgroundColor: "#6D4C41",
                todayTextColor: "#F59E0B",
                arrowColor: "#6D4C41",
              }}
            />
          </Card.Content>
        </Card>
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
});
